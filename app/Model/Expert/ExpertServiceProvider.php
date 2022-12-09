<?php

declare(strict_types=1);

namespace App\Model\Expert;

use App\Model\Expert\Console\FindBooksCommand;
use Illuminate\Support\ServiceProvider;

final class ExpertServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            FindBooksCommand::class,
        ]);
    }
}
