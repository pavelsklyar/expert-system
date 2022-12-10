<?php

declare(strict_types=1);

namespace App\Model\Expert\Question\Entity;

use Illuminate\Database\ConnectionInterface;

final class NextQuestionRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
    }

    public function existsForQuestion(QuestionId $id): bool
    {
        return $this->connection
            ->table('next_questions')
            ->where('question_id', '=', $id)
            ->exists();
    }

    /**
     * @return list<NextQuestion>
     */
    public function getForQuestion(QuestionId $id): array
    {
        $entities = $this->connection
            ->table('next_questions')
            ->where('question_id', '=', $id)
            ->get()
            ->toArray();

        $data = [];
        foreach ($entities as $entity) {
            $condition = null;
            if ($entity->condition !== null) {
                $preparedCondition = json_decode($entity->condition, true);

                $condition = new NextQuestionCondition(
                    operator: NextQuestionConditionOperator::from($preparedCondition['operator']),
                    value: $preparedCondition['value']
                );
            }

            $data[] = new NextQuestion(
                id: NextQuestionId::fromString($entity->id),
                questionId: QuestionId::fromString($entity->question_id),
                nextQuestionId: QuestionId::fromString($entity->next_question_id),
                condition: $condition
            );
        }

        return $data;
    }

    public function persist(NextQuestion $question): void
    {
        $condition = null;
        if ($question->condition !== null) {
            $condition = json_encode([
                'operator' => $question->condition->operator->value,
                'value' => $question->condition->value,
            ]);
        }

        $this->connection
            ->table('next_questions')
            ->upsert(
                [
                    'id' => $question->id,
                    'question_id' => $question->questionId,
                    'next_question_id' => $question->nextQuestionId,
                    'condition' => $condition,
                ],
                ['id'],
                ['question_id', 'next_question_id', 'condition']
            );
    }
}
