<?php

declare(strict_types=1);

namespace App\Model\Question;

use App\Model\Question\Console\LoadQuestionsCommand;
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
