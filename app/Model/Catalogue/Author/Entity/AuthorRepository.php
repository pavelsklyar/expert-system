<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Author\Entity;

use DateTimeImmutable;
use Illuminate\Database\ConnectionInterface;

final class AuthorRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
    }

    /**
     * @param list<AuthorId> $ids
     * @return list<Author>
     */
    public function getMany(array $ids): array
    {
        $authorIds = [];
        foreach ($ids as $id) {
            $authorIds[] = $id->toString();
        }

        $data = $this->connection
            ->table('authors')
            ->whereIn('id', $authorIds)
            ->get()
            ->toArray();

        $entities = [];
        foreach ($data as $entity) {
            $entities[] = Author::create(
                id: AuthorId::fromString($entity->id),
                lastName: $entity->last_name,
                firstName: $entity->first_name,
                middleName: $entity->middle_name,
                createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->created_at),
                updatedAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->updated_at),
            );
        }

        return $entities;
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
