<?php

declare(strict_types=1);

namespace Domain\ValueObject;

readonly class Url
{
    public function __construct(
        public string $value,
    ) {
        if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid URL');
        }
    }
}
