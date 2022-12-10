<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Genre\Entity;

use Illuminate\Database\ConnectionInterface;

final class GenreRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
    }

    public function persist(Genre $genre): void
    {
        $this->connection
            ->table('genres')
            ->upsert(
                [
                    'id' => $genre->id,
                    'title' => $genre->title,
                    'created_at' => $genre->createdAt,
                    'updated_at' => $genre->updatedAt,
                ],
                ['id'],
                ['title', 'updated_at'],
            );
    }
}
