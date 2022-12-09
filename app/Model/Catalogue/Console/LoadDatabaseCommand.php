<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Console;

use App\Model\Author\Entity\Author;
use App\Model\Author\Entity\AuthorId;
use App\Model\Author\Entity\AuthorRepository;
use App\Model\Book\Entity\Book;
use App\Model\Book\Entity\BookId;
use App\Model\Book\Entity\BookRepository;
use App\Model\Book\Entity\Language;
use App\Model\Genre\Entity\Genre;
use App\Model\Genre\Entity\GenreId;
use App\Model\Genre\Entity\GenreRepository;
use Illuminate\Console\Command;

final class LoadDatabaseCommand extends Command
{
    protected $signature = 'app:load-db';

    public function __construct(
        private readonly AuthorRepository $authorRepository,
        private readonly GenreRepository $genreRepository,
        private readonly BookRepository $bookRepository,
    ) {
        parent::__construct();
    }

    public function __invoke(): void
    {
        $classicGenre = new Genre(GenreId::next(), 'Классическая литература');
        $fantasyGenre = new Genre(GenreId::next(), 'Фантастика');
        $businessGenre = new Genre(GenreId::next(), 'Деловая литература');
        $this->saveGenres([$classicGenre, $fantasyGenre, $businessGenre]);

        $stolomatin = new Author(AuthorId::next(), 'Столоматин', 'Александр', 'Сергеевич');
        $pushkin = new Author(AuthorId::next(), 'Пушкин', 'Александр', 'Сергеевич');
        $skerrou = new Author(AuthorId::next(), 'Скэрроу', 'Саймон', null);
        $gluchovsky = new Author(AuthorId::next(), 'Глуховский', 'Дмитрий', null);
        $this->saveAuthors([$stolomatin, $pushkin, $skerrou, $gluchovsky]);

        $this->loadSkerrouBooks($skerrou, $fantasyGenre);
        $this->loadPushkinBooks($pushkin, $classicGenre);
        $this->loadGluchovskyBooks($gluchovsky, $fantasyGenre);
        $this->loadBusinessBooks($stolomatin, $businessGenre);
    }

    /**
     * @param list<Genre> $genres
     */
    private function saveGenres(array $genres): void
    {
        foreach ($genres as $genre) {
            $this->genreRepository->persist($genre);
        }
    }

    /**
     * @param list<Author> $authors
     */
    private function saveAuthors(array $authors): void
    {
        foreach ($authors as $author) {
            $this->authorRepository->persist($author);
        }
    }

    private function loadSkerrouBooks(Author $skerrou, Genre $fantasyGenre): void
    {
        $book1 = new Book(
            id: BookId::next(),
            title: 'Добыча золотого орла',
            description: null,
            rating: 2.9,
            language: Language::English,
            year: 2003,
            authorIds: [$skerrou->id],
            genreIds: [$fantasyGenre->id]
        );
        $book2 = new Book(
            id: BookId::next(),
            title: 'Пророчество орла',
            description: null,
            rating: 4.0,
            language: Language::English,
            year: 2005,
            authorIds: [$skerrou->id],
            genreIds: [$fantasyGenre->id]
        );
        $book3 = new Book(
            id: BookId::next(),
            title: 'Центурион',
            description: null,
            rating: 4.9,
            language: Language::Russian,
            year: 2001,
            authorIds: [$skerrou->id],
            genreIds: [$fantasyGenre->id]
        );

        foreach ([$book1, $book2, $book3] as $book) {
            $this->bookRepository->persist($book);
        }
    }

    private function loadPushkinBooks(Author $author, Genre $genre): void
    {
        $book1 = new Book(
            id: BookId::next(),
            title: 'Анна Каренина',
            description: null,
            rating: 2.9,
            language: Language::English,
            year: 1750,
            authorIds: [$author->id],
            genreIds: [$genre->id]
        );
        $book2 = new Book(
            id: BookId::next(),
            title: 'Бубровский',
            description: null,
            rating: 4.0,
            language: Language::English,
            year: 1780,
            authorIds: [$author->id],
            genreIds: [$genre->id]
        );
        $book3 = new Book(
            id: BookId::next(),
            title: 'Капитанская дочка',
            description: null,
            rating: 4.9,
            language: Language::Russian,
            year: 1790,
            authorIds: [$author->id],
            genreIds: [$genre->id]
        );

        foreach ([$book1, $book2, $book3] as $book) {
            $this->bookRepository->persist($book);
        }
    }

    private function loadGluchovskyBooks(Author $author, Genre $genre): void
    {
        $book1 = new Book(
            id: BookId::next(),
            title: 'Метро 2034',
            description: null,
            rating: 2.9,
            language: Language::Russian,
            year: 2013,
            authorIds: [$author->id],
            genreIds: [$genre->id]
        );
        $book2 = new Book(
            id: BookId::next(),
            title: 'Метро 2035',
            description: null,
            rating: 4.0,
            language: Language::Russian,
            year: 2016,
            authorIds: [$author->id],
            genreIds: [$genre->id]
        );
        $book3 = new Book(
            id: BookId::next(),
            title: 'Метро 2033',
            description: null,
            rating: 4.9,
            language: Language::Russian,
            year: 2010,
            authorIds: [$author->id],
            genreIds: [$genre->id]
        );

        foreach ([$book1, $book2, $book3] as $book) {
            $this->bookRepository->persist($book);
        }
    }

    private function loadBusinessBooks(Author $author, Genre $genre): void
    {
        $book1 = new Book(
            id: BookId::next(),
            title: 'Методология разработки бизнес-тренингов',
            description: null,
            rating: 2.9,
            language: Language::Russian,
            year: 2013,
            authorIds: [$author->id],
            genreIds: [$genre->id]
        );
        $book2 = new Book(
            id: BookId::next(),
            title: 'Персональная инфраструктура',
            description: null,
            rating: 4.0,
            language: Language::Russian,
            year: 2016,
            authorIds: [$author->id],
            genreIds: [$genre->id]
        );
        $book3 = new Book(
            id: BookId::next(),
            title: 'Переговоры',
            description: null,
            rating: 4.9,
            language: Language::Russian,
            year: 2010,
            authorIds: [$author->id],
            genreIds: [$genre->id]
        );

        foreach ([$book1, $book2, $book3] as $book) {
            $this->bookRepository->persist($book);
        }
    }
}
