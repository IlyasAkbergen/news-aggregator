<?php

declare(strict_types=1);

namespace App\UseCase\GetArticles;

use Ramsey\Uuid\UuidInterface;

class RequestModel
{
    public function __construct(
        public ?int $page,
        public ?int $perPage,
        public ?string $search = null,
        public ?\DateTimeImmutable $dateFrom = null,
        public ?UuidInterface $categoryId = null,
        public ?UuidInterface $sourceId = null,
    ) {
    }
}
