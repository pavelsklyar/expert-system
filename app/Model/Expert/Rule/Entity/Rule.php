<?php

declare(strict_types=1);

namespace App\Model\Expert\Rule\Entity;

use App\Model\Catalogue\CatalogueEntity;
use DateTimeImmutable;

final class Rule
{
    public readonly RuleId $id;
    public readonly string $title;
    public readonly CatalogueEntity $entity;
    public readonly string $field;
    public readonly RuleOperator $operator;
    public readonly array $values;
    public DateTimeImmutable $createdAt;
    public DateTimeImmutable $updatedAt;

    public function __construct(
        RuleId $id,
        string $title,
        CatalogueEntity $entity,
        string $field,
        RuleOperator $operator,
        array $values
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->entity = $entity;
        $this->field = $field;
        $this->operator = $operator;
        $this->values = $values;

        $date = new DateTimeImmutable();
        $this->createdAt = $date;
        $this->updatedAt = $date;
    }

    public static function create(
        RuleId $id,
        string $title,
        CatalogueEntity $entity,
        string $field,
        RuleOperator $operator,
        array $values,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        $model = new self($id, $title, $entity, $field, $operator, $values);
        $model->createdAt = $createdAt;
        $model->updatedAt = $updatedAt;

        return $model;
    }
}
