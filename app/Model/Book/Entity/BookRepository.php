<?php

declare(strict_types=1);

namespace App\Model\Book\Entity;

use Illuminate\Database\ConnectionInterface;

final class BookRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
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
