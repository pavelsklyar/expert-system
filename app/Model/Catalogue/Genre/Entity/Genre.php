<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Genre\Entity;

use DateTimeImmutable;

final class Genre
{
    public readonly GenreId $id;
    public readonly string $title;
    public readonly DateTimeImmutable $createdAt;
    public readonly DateTimeImmutable $updatedAt;

    /**
     * @param GenreId $id Идентификатор
     * @param string $title Название
     */
    public function __construct(
        GenreId $id,
        string $title,
    ) {
        $this->id = $id;
        $this->title = $title;

        $date = new DateTimeImmutable();
        $this->createdAt = $date;
        $this->updatedAt = $date;
    }
}
