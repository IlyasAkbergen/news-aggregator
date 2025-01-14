<?php

declare(strict_types=1);

namespace App\Repository;

use App\Factories\AuthorFactory;
use Domain\Entity\Author;
use Domain\Exception\DomainException;
use Domain\Repository\AuthorRepositoryInterface;
use Domain\ValueObject\FullName;
use Ramsey\Uuid\Uuid;

class AuthorRepositoryEloquent implements AuthorRepositoryInterface
{
    /**
     * @throws DomainException
     */
    public function findOrCreateByFullName(FullName $fullName): Author
    {
        return AuthorFactory::fromEloquentModel(
            \App\Models\Author::query()->firstOrCreate([
                'first_name' => $fullName->firstName,
                'last_name' => $fullName->lastName,
            ], [
                'id' => Uuid::uuid4(),
            ]),
        );
    }
}
