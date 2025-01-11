<?php

declare(strict_types=1);

namespace Domain\ValueObject;

use Domain\Exception\DomainException;

readonly class Keyword
{
    /**
     * @throws DomainException
     */
    public function __construct(
        public string $value,
    ) {
        if (empty($this->value)) {
            throw new DomainException('Keyword cannot be empty');
        }
    }
}
