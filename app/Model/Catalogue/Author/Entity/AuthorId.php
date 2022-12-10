<?php

declare(strict_types=1);

namespace App\Model\Catalogue\Author\Entity;

use Webmozart\Assert\Assert;
use function App\Model\Author\Entity\uuid_create;

final class AuthorId
{
    /**
     * @param non-empty-string $value
     */
    private function __construct(
        private readonly string $value,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    public static function next(): self
    {
        /** @var non-empty-string $value */
        $value = uuid_create(UUID_TYPE_DCE);

        return new self($value);
    }

    /**
     * @param non-empty-string $value
     */
    public static function fromString(string $value): self
    {
        Assert::uuid($value);

        return new self($value);
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->value;
    }
}
