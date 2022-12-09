<?php

declare(strict_types=1);

namespace App\Model\Author\Entity;

use Illuminate\Database\ConnectionInterface;

final class AuthorRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
    }

    public function persist(Author $author): void
    {
        $this->connection
            ->table('authors')
            ->upsert(
                [
                    'id' => $author->id,
                    'first_name' => $author->firstName,
                    'last_name' => $author->lastName,
                    'middle_name' => $author->middleName,
                    'created_at' => $author->createdAt,
                    'updated_at' => $author->updatedAt,
                ],
                ['id'],
                ['first_name', 'last_name', 'middle_name', 'updated_at'],
            );
    }
}
