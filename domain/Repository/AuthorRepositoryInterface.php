<?php

declare(strict_types=1);

namespace Domain\Repository;

use Domain\Entity\Author;
use Domain\ValueObject\FullName;

interface AuthorRepositoryInterface
{
    public function findOrCreateByFullName(FullName $fullName): Author;
}
