<?php

declare(strict_types=1);

namespace App\Model\Question\Entity;

/**
 * Следующий вопрос
 */
final class NextQuestion
{
    public readonly NextQuestionId $id;
    public readonly QuestionId $questionId;
    public readonly QuestionId $nextQuestionId;
    public readonly ?NextQuestionCondition $condition;

    public function __construct(
        NextQuestionId $id,
        QuestionId $questionId,
        QuestionId $nextQuestionId,
        ?NextQuestionCondition $condition
    ) {
        $this->id = $id;
        $this->questionId = $questionId;
        $this->nextQuestionId = $nextQuestionId;
        $this->condition = $condition;
    }
}
