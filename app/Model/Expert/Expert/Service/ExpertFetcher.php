<?php

declare(strict_types=1);

namespace App\Model\Expert\Expert\Service;

use App\Model\Catalogue\Book\Entity\Book;
use App\Model\Catalogue\Book\Entity\BookId;
use App\Model\Catalogue\Book\Entity\BookRepository;
use App\Model\Catalogue\CatalogueEntity;
use App\Model\Expert\Condition\Entity\Condition;
use App\Model\Expert\Condition\Entity\ConditionRepository;
use App\Model\Expert\Rule\Entity\RuleRepository;
use Illuminate\Database\ConnectionInterface;

final class ExpertFetcher
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly BookRepository $bookRepository,
        private readonly RuleRepository $ruleRepository,
        private readonly ConditionRepository $conditionRepository,
    ) {
    }

    /**
     * @param array<string, int|string> $answers - Массив [questionId => answer]
     * @return list<Book>
     */
    public function fetch(array $answers): array
    {
        $builder = $this->connection
            ->table('books', 'b')
            ->select(['b.id'])
            ->leftJoin('book_authors as ba', 'ba.book_id', '=', 'b.id')
            ->leftJoin('authors as a', 'a.id', '=', 'ba.author_id')
            ->leftJoin('book_genres as bg', 'bg.book_id', '=', 'b.id')
            ->leftJoin('genres as g', 'g.id', '=', 'bg.genre_id');

        foreach ($this->ruleRepository->all() as $rule) {
            $conditions = $this->conditionRepository->getForRule($rule->id);

            if ($this->isConditionsApplicable($conditions, $answers)) {
                $table = match ($rule->entity) {
                    CatalogueEntity::Author => 'a',
                    CatalogueEntity::Book => 'b',
                    CatalogueEntity::Genre => 'g',
                };

                foreach ($rule->values as $value) {
                    $builder->where(
                        sprintf('%s.%s', $table, $rule->field),
                        $rule->operator->getSign(),
                        $value
                    );
                }
            }
        }

        $ids = $builder->get()->toArray();

        if ($ids === []) {
            return [];
        }

        $bookIds = [];
        foreach ($ids as $id) {
            $bookIds[] = BookId::fromString($id->id);
        }

        return $this->bookRepository->getMany($bookIds);
    }

    /**
     * @param list<Condition> $conditions
     * @param array<string, int|string> $answers - Массив [questionId => answer]
     */
    private function isConditionsApplicable(array $conditions, array $answers): bool
    {
        foreach ($conditions as $condition) {
            if (!isset($answers[$condition->questionId->toString()])) {
                return false;
            }

            $answer = $answers[$condition->questionId->toString()];

            if (!$condition->isApplicable($answer)) {
                return false;
            }
        }

        return true;
    }
}
