<?php

declare(strict_types=1);

namespace Domain\Entity;

use Domain\ValueObject\FullName;

readonly class Author
{
    public function __construct(
        public int $id,
        public FullName $fullName,
    ) {
    }
}
