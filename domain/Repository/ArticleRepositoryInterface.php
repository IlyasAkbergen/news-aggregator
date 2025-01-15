<?php

declare(strict_types=1);

namespace Domain\Repository;

use Domain\DTO\GetArticlesParameters;
use Domain\Entity\Article;
use Domain\Enum\ArticleProviderCode;
use Domain\Service\PaginatorInterface;
use Domain\ValueObject\Url;
use Ramsey\Uuid\UuidInterface;

interface ArticleRepositoryInterface
{
    public function find(UuidInterface $id): ?Article;

    public function save(Article ...$articles): void;

    public function getLatestArticleByProvider(ArticleProviderCode $providerCode): ?Article;

    /**
     * @return PaginatorInterface<Article>
     */
    public function getArticles(GetArticlesParameters $param): PaginatorInterface;

    public function findByUrl(Url $url): ?Article;
}
