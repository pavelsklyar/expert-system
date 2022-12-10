<?php

declare(strict_types=1);

namespace App\Model\Expert\Expert\Console;

use App\Model\Catalogue\Author\Entity\AuthorRepository;
use App\Model\Catalogue\Book\Entity\Book;
use App\Model\Catalogue\Genre\Entity\GenreRepository;
use App\Model\Expert\Expert\Service\ExpertFetcher;
use App\Model\Expert\Question\Entity\NextQuestionRepository;
use App\Model\Expert\Question\Entity\Question;
use App\Model\Expert\Question\Entity\QuestionRepository;
use Illuminate\Console\Command;

class FindBooksCommand extends Command
{
    protected $signature = 'app:expert:run';

    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly NextQuestionRepository $nextQuestionRepository,
        private readonly ExpertFetcher $fetcher,
        private readonly AuthorRepository $authorRepository,
        private readonly GenreRepository $genreRepository,
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

        $books = $this->fetcher->fetch($this->answers);
        $this->drawTable($books);
    }

    private function askQuestion(Question $question): string|int
    {
        $answer = $this->choice($question->text, $question->allowedAnswers);
        $this->answers[$question->id->toString()] = $answer;

        return $answer;
    }

    /**
     * @param list<Book> $books
     */
    private function drawTable(array $books): void
    {
        $data = [];
        foreach ($books as $book) {
            $data[] = [
                'id' => $book->id,
                'title' => $book->title,
                'rating' => $book->rating,
                'language' => $book->language->value,
                'year' => $book->year,
                'authors' => $this->getAuthors($book),
                'genres' => $this->getGenres($book),
            ];
        }

        $this->table(
            ['ID', 'Название', 'Рейтинг', 'Язык', 'Год издания', 'Авторы', 'Жанры'],
            $data
        );
    }

    private function getAuthors(Book $book): string
    {
        $authorIds = [];
        foreach ($book->authors as $author) {
            $authorIds[] = $author->authorId;
        }
        $authorEntities = $this->authorRepository->getMany($authorIds);

        $authors = [];
        foreach ($authorEntities as $authorEntity) {
            $authors[] = sprintf('%s %s', $authorEntity->firstName, $authorEntity->lastName);
        }

        return implode(', ', $authors);
    }

    private function getGenres(Book $book): string
    {
        $genreIds = [];
        foreach ($book->genres as $genre) {
            $genreIds[] = $genre->genreId;
        }
        $genreEntities = $this->genreRepository->getMany($genreIds);

        $genres = [];
        foreach ($genreEntities as $genreEntity) {
            $genres[] = $genreEntity->title;
        }

        return implode(', ', $genres);
    }
}
