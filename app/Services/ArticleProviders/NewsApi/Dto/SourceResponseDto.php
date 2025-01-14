<?php

declare(strict_types=1);

namespace App\Services\ArticleProviders\NewsApi\Dto;

class SourceResponseDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
    ) {
    }

    public static function fromPayload(array $source): self
    {
        return new self(
            $source['id'],
            $source['name'],
        );
    }
}
