<?php

declare(strict_types=1);

namespace Domain\Entity;

use Ramsey\Uuid\UuidInterface;

readonly class Category
{
    public function __construct(
        public UuidInterface $id,
        public string $code,
        public string $name,
    ) {
    }
}
