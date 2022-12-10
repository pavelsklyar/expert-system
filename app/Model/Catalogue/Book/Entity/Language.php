<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Book\Entity;

enum Language: string
{
    case Russian = 'ru';

    case English = 'en';
}
