<?php

declare(strict_types=1);

namespace Domain\Service;

use Domain\Entity\Article;
use Domain\Repository\ArticleRepositoryInterface;

abstract class ArticlesProvider
{
    public function __construct(
        protected ArticleRepositoryInterface $articleRepository,
    ) {
    }

    public function fetchArticles(): void
    {
        $this->articleRepository->save(...$this->getNewArticles());
    }

    /**
     * @return Article[]
     */
    abstract protected function getNewArticles(): array;
}
