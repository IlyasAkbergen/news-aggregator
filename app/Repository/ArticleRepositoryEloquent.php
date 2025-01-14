<?php

declare(strict_types=1);

namespace App\Repository;

use App\Factories\ArticleFactory;
use App\Models\Article as ArticleModel;
use Domain\Entity\Article;
use Domain\Enum\ArticleProviderCode;
use Domain\Exception\DomainException;
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
        $articlesData = array_map(function (Article $article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'description' => $article->description,
                'content' => $article->content,
                'url' => $article->url,
                'image_url' => $article->imageUrl,
                'author_id' => $article->author?->id ?? null,
                'source_id' => $article->source->id,
                'category_id' => $article->category->id,
                'published_at' => $article->publishedAt,
                'provider_code' => $article->providerCode->value,
            ];
        }, $articles);

        ArticleModel::query()->insert($articlesData);
    }

    /**
     * @throws DomainException
     */
    public function getLatestArticleByProvider(ArticleProviderCode $providerCode): ?Article
    {
        $record = ArticleModel::query()
            ->with(['author', 'source', 'category'])
            ->where('provider_code', $providerCode->value)
            ->orderBy('published_at', 'desc')
            ->first();

        if ($record === null) {
            return null;
        }

        return ArticleFactory::fromEloquentModel($record);
    }
}
