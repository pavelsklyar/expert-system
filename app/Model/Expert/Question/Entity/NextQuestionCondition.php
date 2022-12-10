<?php

declare(strict_types=1);

namespace App\Model\Expert\Question\Entity;

/**
 * Условие, при котором будет выбран следующий вопрос.
 */
final class NextQuestionCondition
{
    public function __construct(
        public readonly NextQuestionConditionOperator $operator,
        public readonly int|string $value,
    ) {
    }

    public function isApplicable(int|string $value): bool
    {
        return match ($this->operator) {
            NextQuestionConditionOperator::Equals => $value === $this->value,
            NextQuestionConditionOperator::GreaterThen => $value > $this->value,
            NextQuestionConditionOperator::GreaterThenEq => $value >= $this->value,
            NextQuestionConditionOperator::LessThen => $value < $this->value,
            NextQuestionConditionOperator::LessThenEq => $value <= $this->value,
        };
    }
}
