<?php

declare(strict_types=1);

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\Enum\ArticleProviderCode;
use Domain\ValueObject\Url;
use Ramsey\Uuid\UuidInterface;

readonly class Article
{
    public function __construct(
        public UuidInterface $id,
        public string $title,
        public string $description,
        public string $content,
        public Url $url,
        public ?Url $imageUrl = null,
        public ?Author $author = null,
        public Source $source,
        public Category $category,
        public DateTimeImmutable $publishedAt,
        public ArticleProviderCode $providerCode,
    ) {
    }
}
