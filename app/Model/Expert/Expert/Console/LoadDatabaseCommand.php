<?php

declare(strict_types=1);

namespace App\Model\Expert\Expert\Console;

use App\Model\Catalogue\CatalogueEntity;
use App\Model\Catalogue\Genre\Entity\GenreRepository;
use App\Model\Expert\Condition\Entity\Condition;
use App\Model\Expert\Condition\Entity\ConditionId;
use App\Model\Expert\Condition\Entity\ConditionOperator;
use App\Model\Expert\Condition\Entity\ConditionRepository;
use App\Model\Expert\Question\Entity\NextQuestion;
use App\Model\Expert\Question\Entity\NextQuestionCondition;
use App\Model\Expert\Question\Entity\NextQuestionConditionOperator;
use App\Model\Expert\Question\Entity\NextQuestionId;
use App\Model\Expert\Question\Entity\NextQuestionRepository;
use App\Model\Expert\Question\Entity\Question;
use App\Model\Expert\Question\Entity\QuestionId;
use App\Model\Expert\Question\Entity\QuestionRepository;
use App\Model\Expert\Rule\Entity\Rule;
use App\Model\Expert\Rule\Entity\RuleId;
use App\Model\Expert\Rule\Entity\RuleOperator;
use App\Model\Expert\Rule\Entity\RuleRepository;
use Illuminate\Console\Command;

final class LoadDatabaseCommand extends Command
{
    protected $signature = 'app:expert:load';

    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly NextQuestionRepository $nextQuestionRepository,
        private readonly RuleRepository $ruleRepository,
        private readonly ConditionRepository $conditionRepository,
        private readonly GenreRepository $genreRepository,
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
            allowedAnswers: ['Да', 'Нет', 'Не знаю'],
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
            allowedAnswers: ['Развиваться', 'Отдохнуть', 'Пофантазировать', 'Не знаю'],
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

        $this->setNextQuestions($serialBooks, $language, $year, $author, $authorCorrection, $mood, $rating);

        $this->setLanguageRules($language);
        $this->setYearRules($year);
        $this->setAuthorRules($authorCorrection);
        $this->setMoodRules($mood, $year);
        $this->setRatingRules($rating);
    }

    private function setNextQuestions(Question $serialBooks, Question $language, Question $year, Question $author, Question $authorCorrection, Question $mood, Question $rating): void
    {
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

    private function setLanguageRules(Question $language): void
    {
        $languageRuleEn = new Rule(
            id: RuleId::next(),
            title: 'Книги на английском языке',
            entity: CatalogueEntity::Book,
            field: 'language',
            operator: RuleOperator::Equals,
            values: ['en']
        );
        $languageConditionEn = new Condition(
            id: ConditionId::next(),
            ruleId: $languageRuleEn->id,
            questionId: $language->id,
            operator: ConditionOperator::Equals,
            value: 'Да'
        );
        $languageRuleRu = new Rule(
            id: RuleId::next(),
            title: 'Книги на русском языке',
            entity: CatalogueEntity::Book,
            field: 'language',
            operator: RuleOperator::Equals,
            values: ['ru']
        );
        $languageConditionRu = new Condition(
            id: ConditionId::next(),
            ruleId: $languageRuleRu->id,
            questionId: $language->id,
            operator: ConditionOperator::Equals,
            value: 'Нет'
        );

        $this->ruleRepository->persist($languageRuleEn);
        $this->ruleRepository->persist($languageRuleRu);
        $this->conditionRepository->persist($languageConditionEn);
        $this->conditionRepository->persist($languageConditionRu);
    }

    private function setYearRules(Question $year): void
    {
        $yearRuleNew = new Rule(
            id: RuleId::next(),
            title: 'Новые книги',
            entity: CatalogueEntity::Book,
            field: 'year',
            operator: RuleOperator::GreaterThenEq,
            values: [2000]
        );
        $yearConditionNew = new Condition(
            id: ConditionId::next(),
            ruleId: $yearRuleNew->id,
            questionId: $year->id,
            operator: ConditionOperator::Equals,
            value: 'Новое'
        );
        $yearRuleOld = new Rule(
            id: RuleId::next(),
            title: 'Старые книги',
            entity: CatalogueEntity::Book,
            field: 'year',
            operator: RuleOperator::LessThen,
            values: [2000]
        );
        $yearConditionOld = new Condition(
            id: ConditionId::next(),
            ruleId: $yearRuleOld->id,
            questionId: $year->id,
            operator: ConditionOperator::Equals,
            value: 'Проверенное'
        );

        $this->ruleRepository->persist($yearRuleNew);
        $this->ruleRepository->persist($yearRuleOld);
        $this->conditionRepository->persist($yearConditionNew);
        $this->conditionRepository->persist($yearConditionOld);
    }

    private function setAuthorRules(Question $author): void
    {
        foreach (['Пушкин', 'Глуховский', 'Скэрроу', 'Столоматин'] as $authorLastName) {
            $rule = new Rule(
                id: RuleId::next(),
                title: sprintf('Автор: %s', $authorLastName),
                entity: CatalogueEntity::Author,
                field: 'last_name',
                operator: RuleOperator::Equals,
                values: [$authorLastName]
            );
            $condition = new Condition(
                id: ConditionId::next(),
                ruleId: $rule->id,
                questionId: $author->id,
                operator: ConditionOperator::Equals,
                value: $authorLastName
            );

            $this->ruleRepository->persist($rule);
            $this->conditionRepository->persist($condition);
        }
    }

    private function setMoodRules(Question $mood, Question $year): void
    {
        $genre = $this->genreRepository->getByTitle('Деловая литература');

        $rule = new Rule(
            id: RuleId::next(),
            title: 'Саморазвитие',
            entity: CatalogueEntity::Genre,
            field: 'id',
            operator: RuleOperator::Equals,
            values: [$genre->id->toString()]
        );
        $condition1 = new Condition(
            id: ConditionId::next(),
            ruleId: $rule->id,
            questionId: $mood->id,
            operator: ConditionOperator::Equals,
            value: 'Развиваться'
        );
        $condition2 = new Condition(
            id: ConditionId::next(),
            ruleId: $rule->id,
            questionId: $year->id,
            operator: ConditionOperator::Equals,
            value: 'Новое'
        );

        $this->ruleRepository->persist($rule);
        $this->conditionRepository->persist($condition1);
        $this->conditionRepository->persist($condition2);
    }

    private function setRatingRules(Question $rating): void
    {
        $rule = new Rule(
            id: RuleId::next(),
            title: 'Только рейтинговые книги',
            entity: CatalogueEntity::Book,
            field: 'rating',
            operator: RuleOperator::GreaterThenEq,
            values: [4.0]
        );
        $condition = new Condition(
            id: ConditionId::next(),
            ruleId: $rule->id,
            questionId: $rating->id,
            operator: ConditionOperator::Equals,
            value: 'Да'
        );

        $this->ruleRepository->persist($rule);
        $this->conditionRepository->persist($condition);
    }
}
