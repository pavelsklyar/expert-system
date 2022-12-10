<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Genre\Entity;

use DateTimeImmutable;
use Illuminate\Database\ConnectionInterface;

final class GenreRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
    }

    /**
     * @param list<GenreId> $ids
     * @return list<Genre>
     */
    public function getMany(array $ids): array
    {
        $genreIds = [];
        foreach ($ids as $id) {
            $genreIds[] = $id->toString();
        }

        $data = $this->connection
            ->table('genres')
            ->whereIn('id', $genreIds)
            ->get()
            ->toArray();

        $entities = [];
        foreach ($data as $entity) {
            $entities[] = Genre::create(
                id: GenreId::fromString($entity->id),
                title: $entity->title,
                createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->created_at),
                updatedAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->updated_at),
            );
        }

        return $entities;
    }

    public function getByTitle(string $title): Genre
    {
        /** @var object $entity */
        $entity = $this->connection
            ->table('genres')
            ->where('title', '=', $title)
            ->first();

        return Genre::create(
            id: GenreId::fromString($entity->id),
            title: $entity->title,
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->created_at),
            updatedAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->updated_at),
        );
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
