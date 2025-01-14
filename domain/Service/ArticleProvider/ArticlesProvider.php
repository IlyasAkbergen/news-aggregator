<?php

declare(strict_types=1);

namespace Domain\Service\ArticleProvider;

use Domain\Enum\ArticleProviderCode;

interface ArticlesProvider
{
    public function fetchArticles(): void;

    public function getProviderCode(): ArticleProviderCode;
}
