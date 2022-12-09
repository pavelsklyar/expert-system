<?php

declare(strict_types=1);

namespace App\Model\Book\Entity;

use App\Model\Author\Entity\AuthorId;

class BookAuthor
{
    public function __construct(
        public readonly BookId $bookId,
        public readonly AuthorId $authorId,
    ) {
    }
}
