<?php

declare(strict_types=1);

namespace App\Model\Expert\Condition\Entity;

use App\Model\Expert\Question\Entity\QuestionId;
use App\Model\Expert\Rule\Entity\RuleId;

final class Condition
{
    public readonly ConditionId $id;
    public readonly RuleId $ruleId;
    public readonly QuestionId $questionId;
    public readonly ConditionOperator $operator;
    public readonly int|string $value;

    /**
     * @param ConditionId $id
     * @param RuleId $ruleId
     * @param QuestionId $questionId
     * @param ConditionOperator $operator
     * @param int|string $value
     */
    public function __construct(ConditionId $id, RuleId $ruleId, QuestionId $questionId, ConditionOperator $operator, int|string $value)
    {
        $this->id = $id;
        $this->ruleId = $ruleId;
        $this->questionId = $questionId;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * Выполняется ли условие.
     */
    public function isApplicable(int|string $value): bool
    {
        return match ($this->operator) {
            ConditionOperator::Equals => $value === $this->value,
            ConditionOperator::GreaterThen => $value > $this->value,
            ConditionOperator::GreaterThenEq => $value >= $this->value,
            ConditionOperator::LessThen => $value < $this->value,
            ConditionOperator::LessThenEq => $value <= $this->value,
        };
    }
}
