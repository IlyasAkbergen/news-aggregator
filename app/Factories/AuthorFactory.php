<?php

declare(strict_types=1);

namespace App\Factories;

use App\Models\Author as AuthorEloquentModel;
use Domain\Entity\Author;
use Domain\Exception\DomainException;
use Domain\ValueObject\FullName;

class AuthorFactory
{
    /**
     * @throws DomainException
     */
    public static function fromEloquentModel(AuthorEloquentModel $author): Author
    {
        return new Author(
            id: $author->getId(),
            fullName: new FullName(
                firstName: $author->first_name,
                lastName: $author->last_name,
            ),
        );
    }
}
