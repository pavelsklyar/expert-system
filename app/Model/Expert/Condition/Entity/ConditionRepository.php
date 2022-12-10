<?php

declare(strict_types=1);

namespace App\Model\Expert\Condition\Entity;

use App\Model\Expert\Question\Entity\QuestionId;
use App\Model\Expert\Rule\Entity\RuleId;
use Illuminate\Database\ConnectionInterface;

final class ConditionRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
    }

    /**
     * @return list<Condition>
     */
    public function getForRule(RuleId $ruleId): array
    {
        $data = $this->connection
            ->table('conditions')
            ->where('rule_id', '=', $ruleId)
            ->get()
            ->toArray();

        $conditions = [];
        foreach ($data as $condition) {
            $conditions[] = new Condition(
                id: ConditionId::fromString($condition->id),
                ruleId: RuleId::fromString($condition->rule_id),
                questionId: QuestionId::fromString($condition->question_id),
                operator: ConditionOperator::from($condition->operator),
                value: $condition->value,
            );
        }

        return $conditions;
    }

    public function persist(Condition $condition): void
    {
        $this->connection
            ->table('conditions')
            ->upsert(
                [
                    'id' => $condition->id,
                    'rule_id' => $condition->ruleId,
                    'question_id' => $condition->questionId,
                    'operator' => $condition->operator->value,
                    'value' => $condition->value,
                ],
                ['id'],
                ['rule_id', 'question_id', 'operator', 'value']
            );
    }
}
