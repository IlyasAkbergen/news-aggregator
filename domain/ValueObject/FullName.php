<?php

declare(strict_types=1);

namespace Domain\ValueObject;

use Domain\Exception\DomainException;

readonly class FullName
{
    /**
     * @throws DomainException
     */
    public function __construct(
        public string $firstName,
        public string $lastName,
    ) {
        if (empty($this->firstName) || empty($this->lastName)) {
            throw new DomainException('First name and last name cannot be empty');
        }
    }

    public function __toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}

