<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Author\Entity;

use DateTimeImmutable;

final class Author
{
    public readonly AuthorId $id;
    public readonly string $lastName;
    public readonly string $firstName;
    public readonly ?string $middleName;
    public DateTimeImmutable $createdAt;
    public DateTimeImmutable $updatedAt;

    /**
     * @param AuthorId $id Идентификатор
     * @param string $lastName Фамилия
     * @param string $firstName Имя
     * @param string|null $middleName Отчество
     */
    public function __construct(
        AuthorId $id,
        string $lastName,
        string $firstName,
        ?string $middleName,
    ) {
        $this->id = $id;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->middleName = $middleName;

        $date = new DateTimeImmutable();
        $this->createdAt = $date;
        $this->updatedAt = $date;
    }

    public static function create(AuthorId $id, string $lastName, string $firstName, ?string $middleName, DateTimeImmutable $createdAt, DateTimeImmutable $updatedAt): self
    {
        $entity = new self($id, $lastName, $firstName, $middleName);
        $entity->createdAt = $createdAt;
        $entity->updatedAt = $updatedAt;

        return $entity;
    }
}
