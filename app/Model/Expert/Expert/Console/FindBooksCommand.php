<?php

declare(strict_types=1);

namespace App\Model\Expert\Expert\Console;

use App\Model\Expert\Question\Entity\NextQuestionRepository;
use App\Model\Expert\Question\Entity\Question;
use App\Model\Expert\Question\Entity\QuestionRepository;
use Illuminate\Console\Command;

class FindBooksCommand extends Command
{
    protected $signature = 'app:find-books';

    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly NextQuestionRepository $nextQuestionRepository,
        private array $questions = [],
        private array $answers = [],
    ) {
        parent::__construct();
    }

    public function __invoke(): void
    {
        $question = $this->questionRepository->getFirst();

        while (true) {
            $answer = $this->askQuestion($question);

            if (!$this->nextQuestionRepository->existsForQuestion($question->id)) {
                break;
            }

            $nextQuestions = $this->nextQuestionRepository->getForQuestion($question->id);

            $nextQuestionId = null;
            foreach ($nextQuestions as $nextQuestion) {
                if ($nextQuestion->condition === null) {
                    $nextQuestionId = $nextQuestion->nextQuestionId;

                    continue;
                }

                if (!$nextQuestion->condition->isApplicable($answer)) {
                    continue;
                }

                $nextQuestionId = $nextQuestion->nextQuestionId;
                break;
            }

            if ($nextQuestionId === null) {
                break;
            }

            $question = $this->questionRepository->get($nextQuestionId);
        }

        dd($this->answers);
    }

    private function askQuestion(Question $question): string|int
    {
        $answer = $this->choice($question->text, $question->allowedAnswers);

        $this->questions[] = $question->id->toString();
        $this->answers[$question->id->toString()] = $answer;

        return $answer;
    }
}
