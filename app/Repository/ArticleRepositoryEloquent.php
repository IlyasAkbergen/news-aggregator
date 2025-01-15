<?php

declare(strict_types=1);

namespace App\Repository;

use App\Factories\ArticleFactory;
use App\Models\Article as ArticleModel;
use App\Services\LaravelPaginatorAdapter;
use Domain\DTO\GetArticlesParameters;
use Domain\Entity\Article;
use Domain\Enum\ArticleProviderCode;
use Domain\Exception\DomainException;
use Domain\Repository\ArticleRepositoryInterface;
use Domain\Service\PaginatorInterface;
use Domain\ValueObject\Url;
use Ramsey\Uuid\UuidInterface;

class ArticleRepositoryEloquent implements ArticleRepositoryInterface
{
    /**
     * @throws DomainException
     */
    public function find(UuidInterface $id): ?Article
    {
        $record = ArticleModel::query()
            ->with(['author', 'source', 'category'])
            ->where('id', $id->toString())
            ->first();

        if ($record === null) {
            return null;
        }

        return ArticleFactory::fromEloquentModel($record);
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
                'author_id' => $article->author->id ?? null,
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

    public function getArticles(GetArticlesParameters $param): PaginatorInterface
    {
        $paginator = ArticleModel::query()
            ->with(['author', 'source', 'category'])
            ->when($param->sourceId, fn ($q, $sourceId) => $q->where('source_id', $sourceId))
            ->when($param->categoryId, fn ($q, $categoryId) => $q->where('category_id', $categoryId))
            ->when($param->search, fn ($q, $search) => $q->where('title', 'like', "%$search%"))
            ->paginate(
                perPage: $param->perPage,
                page: $param->page,
            );

        return new LaravelPaginatorAdapter(
            paginator: $paginator,
            itemsTransformer: fn (ArticleModel $record) => ArticleFactory::fromEloquentModel($record),
        );
    }

    /**
     * @throws DomainException
     */
    public function findByUrl(Url $url): ?Article
    {
        $record = ArticleModel::query()
            ->where('url', $url->value)
            ->first();

        if ($record === null) {
            return null;
        }

        return ArticleFactory::fromEloquentModel($record);
    }
}
