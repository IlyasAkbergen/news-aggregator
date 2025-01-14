<?php

declare(strict_types=1);

namespace App\Services\ArticleProviders\NewsApi;

use App\Jobs\SaveNewsApiArticleJob;
use App\Services\ArticleProviders\NewsApi\Dto\ArticleResponseDto;
use DateTimeImmutable;
use Domain\Entity\Source;
use Domain\Enum\ArticleProviderCode;
use Domain\Exception\ExternalException;
use Domain\Repository\ArticleRepositoryInterface;
use Domain\Repository\SourceRepositoryInterface;
use Domain\Service\ArticleProvider\ArticlesProvider;

class NewsApiArticlesProvider implements ArticlesProvider
{
    /**
     * @var Source[] $remoteSources
     */
    private array $remoteSources = [];

    public function __construct(
        readonly private ArticleRepositoryInterface $articleRepository,
        readonly private SourceRepositoryInterface $sourceRepository,
        readonly private NewsApiClient $newsApiClient,
    ) {
    }

    /**
     * @throws ExternalException
     */
    public function fetchArticles(): void
    {
        $latestArticle = $this->articleRepository->getLatestArticleByProvider($this->getProviderCode());
        $sources = $this->sourceRepository->getSources();
        if ($sources === []) {
            $sources = $this->getSources();
            $this->sourceRepository->save(...$sources);
        }
        $sourceCodes = array_map(
            static fn (Source $source) => $source->code,
            $sources,
        );
        $articles = $this->newsApiClient->getArticles(
            sourceCodes: $sourceCodes,
            from: $latestArticle->publishedAt ?? $this->getDefaultLatestArticleDate(),
        );

        foreach ($articles as $article) {
            if ($article['source']['id'] === null) {
                continue;
            }

            SaveNewsApiArticleJob::dispatch(ArticleResponseDto::fromPayload($article));
        }
    }

    public function getProviderCode(): ArticleProviderCode
    {
        return ArticleProviderCode::NEWS_API;
    }

    /**
     * @return Source[]
     * @throws ExternalException
     */
    public function getSources(): array
    {
        if ($this->remoteSources === []) {
            $this->remoteSources = $this->newsApiClient->getSources();
        }

        return $this->remoteSources;
    }

    private function getDefaultLatestArticleDate(): DateTimeImmutable
    {
        return new DateTimeImmutable('-29 day');
    }
}
