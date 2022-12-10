<?php

declare(strict_types=1);

namespace App\Model\Expert\Rule\Entity;

enum RuleOperator: string
{
    case Equals = 'equals';
    case GreaterThen = 'greaterThen';
    case GreaterThenEq = 'greaterThenEq';
    case LessThen = 'lessThen';
    case LessThenEq = 'lessThenEq';

    public function getSign(): string
    {
        return match ($this) {
            self::Equals => '=',
            self::GreaterThen => '>',
            self::GreaterThenEq => '>=',
            self::LessThen => '<',
            self::LessThenEq => '<=',
        };
    }
}
