<?php

declare(strict_types=1);

namespace App\Model\Question\Entity;

/**
 * Оператор условия
 */
enum NextQuestionConditionOperator: string
{
    case Equals = 'equals';

    case GreaterThen = 'greaterThen';

    case GreaterThenEq = 'greaterThenEq';

    case LessThen = 'lessThen';

    case LessThenEq = 'lessThenEq';
}
