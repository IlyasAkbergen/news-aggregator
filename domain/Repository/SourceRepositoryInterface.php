<?php

declare(strict_types=1);

namespace Domain\Repository;

use Domain\Entity\Source;

interface SourceRepositoryInterface
{
    /**
     * @return Source[]
     */
    public function getSources(): array;

    public function save(Source ...$sources): void;

    public function findByCode(string $code): ?Source;
}
