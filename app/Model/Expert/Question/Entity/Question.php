<?php

declare(strict_types=1);

namespace App\Model\Expert\Question\Entity;

use DateTimeImmutable;

final class Question
{
    public readonly QuestionId $id;
    public readonly string $text;

    /**
     * @var list<int|string>
     */
    public readonly array $allowedAnswers;

    public readonly bool $first;
    public DateTimeImmutable $createdAt;
    public DateTimeImmutable $updatedAt;

    public function __construct(QuestionId $id, string $text, array $allowedAnswers, bool $first = false)
    {
        $this->id = $id;
        $this->text = $text;
        $this->allowedAnswers = $allowedAnswers;
        $this->first = $first;

        $date = new DateTimeImmutable();
        $this->createdAt = $date;
        $this->updatedAt = $date;
    }

    public static function create(
        QuestionId $id,
        string $text,
        array $allowedAnswers,
        bool $first,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        $entity = new self($id, $text, $allowedAnswers, $first);
        $entity->createdAt = $createdAt;
        $entity->updatedAt = $updatedAt;

        return $entity;
    }
}
