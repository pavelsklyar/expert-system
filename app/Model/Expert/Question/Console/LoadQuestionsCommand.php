<?php

declare(strict_types=1);

namespace App\Model\Expert\Question\Console;

use App\Model\Expert\Question\Entity\NextQuestion;
use App\Model\Expert\Question\Entity\NextQuestionCondition;
use App\Model\Expert\Question\Entity\NextQuestionConditionOperator;
use App\Model\Expert\Question\Entity\NextQuestionId;
use App\Model\Expert\Question\Entity\NextQuestionRepository;
use App\Model\Expert\Question\Entity\Question;
use App\Model\Expert\Question\Entity\QuestionId;
use App\Model\Expert\Question\Entity\QuestionRepository;
use Illuminate\Console\Command;

final class LoadQuestionsCommand extends Command
{
    protected $signature = 'app:load-questions';

    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly NextQuestionRepository $nextQuestionRepository,
    ) {
        parent::__construct();
    }

    public function __invoke(): void
    {
        $serialBooks = new Question(
            id: QuestionId::next(),
            text: 'На сколько книг будет растянута ваша идеальная история?',
            allowedAnswers: ['<3', '>=3'],
            first: true,
        );

        $language = new Question(
            id: QuestionId::next(),
            text: 'Хотели бы вы прочитать книгу на иностранном языке?',
            allowedAnswers: ['Да', 'Нет'],
        );

        $year = new Question(
            id: QuestionId::next(),
            text: 'Хотите что-то новое или лучше проверенное временем?',
            allowedAnswers: ['Новое', 'Проверенное', 'Не важно'],
        );

        $author = new Question(
            id: QuestionId::next(),
            text: 'Есть ли у вас любимые авторы?',
            allowedAnswers: ['Да', 'Нет'],
        );

        $authorCorrection = new Question(
            id: QuestionId::next(),
            text: 'Выберите автора из списка',
            allowedAnswers: [
                'Пушкин',
                'Глуховский',
                'Скэрроу',
                'Столоматин',
                'Никто не нравится',
            ],
        );

        $mood = new Question(
            id: QuestionId::next(),
            text: 'Какое у вас настроение?',
            allowedAnswers: ['Развиваться', 'Отдохнуть', 'Пофантазировать'],
        );

        $rating = new Question(
            id: QuestionId::next(),
            text: 'Доверяете ли оценке других людей?',
            allowedAnswers: ['Да', 'Нет'],
        );

        $this->questionRepository->persist($serialBooks);
        $this->questionRepository->persist($language);
        $this->questionRepository->persist($year);
        $this->questionRepository->persist($author);
        $this->questionRepository->persist($authorCorrection);
        $this->questionRepository->persist($mood);
        $this->questionRepository->persist($rating);

        $this->nextQuestionRepository->persist(
            new NextQuestion(
                id: NextQuestionId::next(),
                questionId: $serialBooks->id,
                nextQuestionId: $language->id,
                condition: null,
            )
        );

        $this->nextQuestionRepository->persist(
            new NextQuestion(
                id: NextQuestionId::next(),
                questionId: $language->id,
                nextQuestionId: $year->id,
                condition: null,
            )
        );

        $this->nextQuestionRepository->persist(
            new NextQuestion(
                id: NextQuestionId::next(),
                questionId: $year->id,
                nextQuestionId: $author->id,
                condition: null,
            )
        );

        $this->nextQuestionRepository->persist(
            new NextQuestion(
                id: NextQuestionId::next(),
                questionId: $author->id,
                nextQuestionId: $authorCorrection->id,
                condition: new NextQuestionCondition(
                    operator: NextQuestionConditionOperator::Equals,
                    value: 'Да'
                ),
            )
        );

        $this->nextQuestionRepository->persist(
            new NextQuestion(
                id: NextQuestionId::next(),
                questionId: $author->id,
                nextQuestionId: $mood->id,
                condition: null,
            )
        );

        $this->nextQuestionRepository->persist(
            new NextQuestion(
                id: NextQuestionId::next(),
                questionId: $authorCorrection->id,
                nextQuestionId: $mood->id,
                condition: null,
            )
        );

        $this->nextQuestionRepository->persist(
            new NextQuestion(
                id: NextQuestionId::next(),
                questionId: $mood->id,
                nextQuestionId: $rating->id,
                condition: null,
            )
        );
    }
}
