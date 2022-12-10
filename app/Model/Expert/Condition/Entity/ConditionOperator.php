<?php

declare(strict_types=1);

namespace App\Model\Expert\Condition\Entity;

enum ConditionOperator: string
{
    case Equals = 'equals';

    case GreaterThen = 'greaterThen';

    case GreaterThenEq = 'greaterThenEq';

    case LessThen = 'lessThen';

    case LessThenEq = 'lessThenEq';
}
