<?php

declare(strict_types=1);

namespace App\Model\Expert\Rule\Entity;

use App\Model\Catalogue\CatalogueEntity;
use DateTimeImmutable;
use Illuminate\Database\ConnectionInterface;

final class RuleRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
    }

    /**
     * @return iterable<Rule>
     */
    public function all(): iterable
    {
        $data = $this->connection
            ->table('rules')
            ->get()
            ->toArray();

        foreach ($data as $rule) {
            yield Rule::create(
                id: RuleId::fromString($rule->id),
                title: $rule->title,
                entity: CatalogueEntity::from($rule->entity),
                field: $rule->field,
                operator: RuleOperator::from($rule->operator),
                values: json_decode($rule->values, true),
                createdAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $rule->created_at),
                updatedAt: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $rule->updated_at),
            );
        }
    }

    public function persist(Rule $rule): void
    {
        $this->connection
            ->table('rules')
            ->upsert(
                [
                    'id' => $rule->id,
                    'title' => $rule->title,
                    'entity' => $rule->entity->value,
                    'field' => $rule->field,
                    'operator' => $rule->operator->value,
                    'values' => json_encode($rule->values),
                    'created_at' => $rule->createdAt,
                    'updated_at' => $rule->updatedAt,
                ],
                ['id'],
                ['title', 'entity', 'field', 'operator', 'values', 'updated_at']
            );
    }
}
