<?php

declare(strict_types=1);

namespace Domain\ValueObject;

readonly class KeywordsCollection
{
    public array $keywords;

    public function __construct(
        Keyword ...$keywords,
    ) {
        $this->keywords = $keywords;
    }
}
