<?php

declare(strict_types=1);

namespace Domain\Repository;

use Domain\Entity\Article;

interface ArticleRepositoryInterface
{
    public function find(int $id): ?Article;

    public function save(Article ...$articles): void;
}
