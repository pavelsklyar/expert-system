<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Book\Entity;

use App\Model\Catalogue\Genre\Entity\GenreId;
use DateTimeImmutable;

final class Book
{
    public readonly BookId $id;
    public readonly string $title;
    public readonly ?string $description;
    public readonly float $rating;
    public readonly Language $language;
    public readonly int $year;

    /**
     * @var GenreId
     */
    public readonly array $authors;

    /**
     * @var GenreId
     */
    public readonly array $genres;

    public readonly DateTimeImmutable $createdAt;
    public readonly DateTimeImmutable $updatedAt;

    /**
     * @param BookId $id Идентификатор
     * @param string $title Название
     * @param ?string $description Описание
     * @param float $rating Рейтинг
     * @param Language $language Язык
     * @param int $year Год издания
     * @param GenreId $authorIds Авторы
     * @param GenreId $genreIds Жанры
     */
    public function __construct(
        BookId $id,
        string $title,
        ?string $description,
        float $rating,
        Language $language,
        int $year,
        array $authorIds,
        array $genreIds,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->rating = $rating;
        $this->language = $language;
        $this->year = $year;

        $authors = [];
        foreach ($authorIds as $authorId) {
            $authors[] = new BookAuthor($id, $authorId);
        }
        $this->authors = $authors;

        $genres = [];
        foreach ($genreIds as $genreId) {
            $genres[] = new BookGenre($id, $genreId);
        }
        $this->genres = $genres;

        $date = new DateTimeImmutable();
        $this->createdAt = $date;
        $this->updatedAt = $date;
    }
}
