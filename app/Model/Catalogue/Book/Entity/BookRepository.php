<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Book\Entity;

use App\Model\Catalogue\Author\Entity\AuthorId;
use App\Model\Catalogue\Genre\Entity\GenreId;
use DateTimeImmutable;
use Illuminate\Database\ConnectionInterface;

final class BookRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
    }

    /**
     * @param list<BookId> $ids
     * @return list<Book>
     */
    public function getMany(array $ids): array
    {
        $bookIds = [];
        foreach ($ids as $id) {
            $bookIds[] = $id->toString();
        }

        $data = $this->connection
            ->table('books', 'b')
            ->selectRaw(<<<'SQL'
                b.*,
                string_agg(distinct ba.author_id::varchar, ',') as authors,
                string_agg(distinct bg.genre_id::varchar, ',') as genres
            SQL)
            ->leftJoin('book_authors as ba', 'ba.book_id', '=', 'b.id')
            ->leftJoin('book_genres as bg', 'bg.book_id', '=', 'b.id')
            ->whereIn('id', $bookIds)
            ->groupBy('b.id')
            ->get()
            ->toArray();

        $entities = [];
        foreach ($data as $item) {
            $authors = [];
            foreach (explode(',', $item->authors) as $authorId) {
                $authors[] = AuthorId::fromString($authorId);
            }

            $genres = [];
            foreach (explode(',', $item->genres) as $genreId) {
                $genres[] = GenreId::fromString($genreId);
            }
            
            $entities[] = Book::create(
                id: BookId::fromString($item->id),
                title: $item->title,
                description: $item->description,
                rating: (float)$item->rating,
                language: Language::from($item->language),
                year: $item->year,
                authorIds: $authors,
                genreIds: $genres,
                createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $item->created_at),
                updatedAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $item->updated_at),
            );
        }

        return $entities;
    }

    public function persist(Book $book): void
    {
        $this->connection
            ->table('books')
            ->upsert(
                [
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description,
                    'rating' => $book->rating,
                    'language' => $book->language->value,
                    'year' => $book->year,
                    'created_at' => $book->createdAt,
                    'updated_at' => $book->updatedAt,
                ],
                ['id'],
                ['title', 'description', 'rating', 'language', 'year', 'updated_at'],
            );

        if ($book->authors !== []) {
            $this->connection
                ->table('book_authors')
                ->where('book_id', '=', $book->id)
                ->delete();

            foreach ($book->authors as $bookAuthor) {
                $this->connection
                    ->table('book_authors')
                    ->insert([
                        'book_id' => $bookAuthor->bookId,
                        'author_id' => $bookAuthor->authorId,
                    ]);
            }
        }

        if ($book->genres !== []) {
            $this->connection
                ->table('book_genres')
                ->where('book_id', '=', $book->id)
                ->delete();

            foreach ($book->genres as $bookGenre) {
                $this->connection
                    ->table('book_genres')
                    ->insert([
                        'book_id' => $bookGenre->bookId,
                        'genre_id' => $bookGenre->genreId,
                    ]);
            }
        }
    }
}
