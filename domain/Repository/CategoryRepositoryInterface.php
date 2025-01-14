<?php

declare(strict_types=1);

namespace Domain\Repository;

use Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    public function findOrCreateByCode(string $code): Category;
}
