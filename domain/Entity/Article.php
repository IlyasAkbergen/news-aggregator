<?php

declare(strict_types=1);

namespace Domain\Entity;

use DateTimeImmutable;
use Domain\ValueObject\KeywordsCollection;

readonly class Article
{
    public function __construct(
        public ?int $id = null,
        public string $title,
        public string $content,
        public Author $author,
        public Source $source,
        public Category $category,
        public DateTimeImmutable $publishedAt,
        public KeywordsCollection $keywords,
    ) {
    }
}
