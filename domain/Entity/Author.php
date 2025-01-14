<?php

declare(strict_types=1);

namespace Domain\Entity;

use Domain\ValueObject\FullName;
use Ramsey\Uuid\UuidInterface;

readonly class Author
{
    public function __construct(
        public UuidInterface $id,
        public FullName $fullName,
    ) {
    }
}
