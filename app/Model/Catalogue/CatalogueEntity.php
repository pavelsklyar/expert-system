<?php

declare(strict_types=1);

namespace App\Model\Catalogue;

enum CatalogueEntity: string
{
    case Author = 'author';
    case Book = 'book';
    case Genre = 'genre';
}
