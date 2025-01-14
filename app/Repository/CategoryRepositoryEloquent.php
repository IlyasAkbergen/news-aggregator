<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Category as CategoryModel;
use Domain\Entity\Category;
use Domain\Repository\CategoryRepositoryInterface;
use Ramsey\Uuid\Uuid;

class CategoryRepositoryEloquent implements CategoryRepositoryInterface
{
    public function findOrCreateByCode(string $code): Category
    {
        $record = CategoryModel::query()->firstOrCreate(
            [
                'code' => $code
            ],
            [
                'id' => Uuid::uuid4(),
                'name' => $code
            ],
        );

        return new Category(
            id: $record->getId(),
            code: $record->code,
            name: $record->name,
        );
    }
}
