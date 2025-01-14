<?php

declare(strict_types=1);

namespace App\Services\ArticleProviders\NewsApi\Dto;

class ArticleResponseDto
{
    public function __construct(
        public readonly SourceResponseDto $source,
        public readonly string $title,
        public readonly string $description,
        public readonly string $url,
        public readonly string $publishedAt,
        public readonly string $content,
        public readonly ?string $author = null,
        public readonly ?string $urlToImage = null,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self(
            source: SourceResponseDto::fromPayload($payload[ 'source']),
            title: $payload[ 'title'],
            description: $payload[ 'description'],
            url: $payload[ 'url'],
            publishedAt: $payload[ 'publishedAt'],
            content: $payload[ 'content'],
            author: $payload[ 'author'],
            urlToImage: $payload[ 'urlToImage'],
        );
    }
}
