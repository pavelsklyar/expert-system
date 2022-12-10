<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Book\Entity;

use App\Model\Catalogue\Genre\Entity\GenreId;

class BookGenre
{
    public function __construct(
        public readonly BookId $bookId,
        public readonly GenreId $genreId,
    ) {
    }
}
