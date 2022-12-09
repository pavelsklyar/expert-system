<?php

declare(strict_types=1);

namespace App\Model\Book\Entity;

use App\Model\Genre\Entity\GenreId;

class BookGenre
{
    public function __construct(
        public readonly BookId $bookId,
        public readonly GenreId $genreId,
    ) {
    }
}
