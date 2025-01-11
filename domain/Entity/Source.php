<?php

declare(strict_types=1);

namespace Domain\Entity;

use Domain\ValueObject\Url;

readonly class Source
{
    public function __construct(
        public int $id,
        public string $name,
        public Url $url,
    ) {
    }
}
