<?php

declare(strict_types=1);

namespace Domain\DTO;

use Ramsey\Uuid\UuidInterface;

class GetArticlesParameters
{
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $search = null,
        public ?\DateTimeImmutable $dateFrom = null,
        public ?UuidInterface $categoryId = null,
        public ?UuidInterface $sourceId = null,
    ) {
    }
}
