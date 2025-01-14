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
        if (empty($this->firstName)) {
            throw new DomainException('First name cannot be empty');
        }
    }

    /**
     * @throws DomainException
     */
    public static function fromString(string $fullName): self
    {
        $parts = explode(' ', $fullName);

        if (count($parts) == 2) {
            return new self($parts[0], $parts[1]);
        }

        return new self($parts[0], '');
    }

    public function __toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}

