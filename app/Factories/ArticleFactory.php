<?php

declare(strict_types=1);

namespace App\Factories;

use App\Models\Article as ArticleModel;
use Domain\Entity\Article;
use Domain\Entity\Category;
use Domain\Entity\Source;
use Domain\Exception\DomainException;
use Domain\ValueObject\Url;

class ArticleFactory
{
    /**
     * @throws DomainException
     */
    public static function fromEloquentModel(ArticleModel $model): Article
    {
        return new Article(
            id: $model->getId(),
            title: $model->title,
            description: $model->description,
            content: $model->content,
            url: new Url($model->url),
            imageUrl: $model->image_url !== null ? new Url($model->image_url) : null,
            author: $model->author !== null ? AuthorFactory::fromEloquentModel($model->author) : null,
            source: new Source(
                id: $model->source->getId(),
                code: $model->source->code,
                name: $model->source->name,
            ),
            category: new Category(
                id: $model->category->getId(),
                code: $model->category->code,
                name: $model->category->name,
            ),
            publishedAt: $model->published_at->toDateTimeImmutable(),
            providerCode: $model->provider_code,
        );
    }
}
