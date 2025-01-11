<?php

declare(strict_types=1);

namespace App\Repository;

use Domain\Entity\Article;
use Domain\Repository\ArticleRepositoryInterface;

class ArticleRepositoryEloquent implements ArticleRepositoryInterface
{
    public function find(int $id): ?Article
    {
        // TODO: Implement find() method.
        return null;
    }

    public function save(Article ...$articles): void
    {
        // TODO: Implement save() method.
    }
}
