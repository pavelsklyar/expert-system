<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Genre\Entity;

use DateTimeImmutable;

final class Genre
{
    public readonly GenreId $id;
    public readonly string $title;
    public DateTimeImmutable $createdAt;
    public DateTimeImmutable $updatedAt;

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

    public static function create(GenreId $id, string $title, DateTimeImmutable $createdAt, DateTimeImmutable $updatedAt): self
    {
        $entity = new self($id, $title);
        $entity->createdAt = $createdAt;
        $entity->updatedAt = $updatedAt;

        return $entity;
    }
}
