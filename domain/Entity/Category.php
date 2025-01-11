<?php

declare(strict_types=1);

namespace Domain\Entity;

readonly class Category
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
}
