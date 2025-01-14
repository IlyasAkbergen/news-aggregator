<?php

declare(strict_types=1);

namespace Domain\Enum;

enum ArticleProviderCode: string
{
    case NEWS_API = 'newsapi';

    public static function values(): array
    {
        return array_map(static fn (self $value) => $value->value, self::cases());
    }
}
