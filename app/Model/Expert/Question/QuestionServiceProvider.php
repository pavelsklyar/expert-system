<?php

declare(strict_types=1);

namespace App\Model\Expert\Question;

use App\Model\Expert\Question\Console\LoadQuestionsCommand;
use Illuminate\Support\ServiceProvider;

class QuestionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            LoadQuestionsCommand::class,
        ]);
    }
}
