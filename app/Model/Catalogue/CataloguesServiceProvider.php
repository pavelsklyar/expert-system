<?php

declare(strict_types=1);

namespace App\Model\Catalogue;

use App\Model\Catalogue\Console\LoadDatabaseCommand;
use Illuminate\Support\ServiceProvider;

final class CataloguesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            LoadDatabaseCommand::class,
        ]);
    }
}
