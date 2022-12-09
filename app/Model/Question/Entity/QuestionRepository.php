<?php

declare(strict_types=1);

namespace App\Model\Question\Entity;

use DateTimeImmutable;
use DomainException;
use Illuminate\Database\ConnectionInterface;

final class QuestionRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
    }

    public function exists(QuestionId $id): bool
    {
        return $this->connection
            ->table('questions')
            ->where('id', '=', $id)
            ->exists();
    }

    public function get(QuestionId $id): Question
    {
        /** @var object|null $entity */
        $entity = $this->connection
            ->table('questions')
            ->where('id', '=', $id)
            ->first();

        if ($entity === null) {
            throw new DomainException();
        }

        return Question::create(
            id: QuestionId::fromString($entity->id),
            text: $entity->text,
            allowedAnswers: json_decode($entity->allowed_answers, true),
            first: $entity->first,
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->created_at),
            updatedAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->created_at),
        );
    }

    public function getFirst(): Question
    {
        /** @var object $entity */
        $entity = $this->connection
            ->table('questions')
            ->where('first', '=', true)
            ->first();

        return Question::create(
            id: QuestionId::fromString($entity->id),
            text: $entity->text,
            allowedAnswers: json_decode($entity->allowed_answers, true),
            first: $entity->first,
            createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->created_at),
            updatedAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->created_at),
        );
    }

    /**
     * @return list<Question>
     */
    public function getMany(array $ids): array
    {
        $entities = $this->connection
            ->table('questions')
            ->whereIn('id', $ids)
            ->first();

        $data = [];
        foreach ($entities as $entity) {
            $data[] = Question::create(
                id: QuestionId::fromString($entity->id),
                text: $entity->text,
                allowedAnswers: json_decode($entity->allowed_answers, true),
                first: $entity->first === 1,
                createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->created_at),
                updatedAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $entity->created_at),
            );
        }

        return $data;
    }

    public function persist(Question $question): void
    {
        $this->connection
            ->table('questions')
            ->upsert(
                [
                    'id' => $question->id,
                    'text' => $question->text,
                    'allowed_answers' => json_encode($question->allowedAnswers, JSON_THROW_ON_ERROR),
                    'first' => $question->first,
                    'created_at' => $question->createdAt,
                    'updated_at' => $question->updatedAt,
                ],
                ['id'],
                ['text', 'allowed_answers', 'first', 'updated_at']
            );
    }
}
