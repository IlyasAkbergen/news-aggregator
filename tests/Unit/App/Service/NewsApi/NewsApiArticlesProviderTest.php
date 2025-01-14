<?php

declare(strict_types=1);

namespace Tests\Unit\App\Service\NewsApi;

use App\Jobs\SaveNewsApiArticleJob;
use App\Services\ArticleProviders\NewsApi\Dto\ArticleResponseDto;
use App\Services\ArticleProviders\NewsApi\NewsApiArticlesProvider;
use App\Services\ArticleProviders\NewsApi\NewsApiClient;
use Domain\Exception\ExternalException;
use Domain\Repository\ArticleRepositoryInterface;
use Domain\Repository\SourceRepositoryInterface;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class NewsApiArticlesProviderTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ExternalException
     */
    public function testFetchArticles(): void
    {
        Queue::fake();

        $newsApiClient = self::createMock(NewsApiClient::class);
        $newsApiClient->expects(self::once())
            ->method('getArticles')
            ->willReturn([
                [
                    'source' => [
                        'id' => 'source1',
                        'name' => 'Source 1',
                    ],
                    'author' => 'Author 1',
                    'title' => 'Title 1',
                    'description' => 'Description 1',
                    'url' => 'https://example.com/1',
                    'urlToImage' => 'https://example.com/1.jpg',
                    'publishedAt' => '2021-01-01T00:00:00Z',
                    'content' => 'Content 1',
                ],
                [
                    'source' => [
                        'id' => 'source2',
                        'name' => 'Source 2',
                    ],
                    'author' => 'Author 2',
                    'title' => 'Title 2',
                    'description' => 'Description 2',
                    'url' => 'https://example.com/2',
                    'urlToImage' => 'https://example.com/2.jpg',
                    'publishedAt' => '2021-01-02T00:00:00Z',
                    'content' => 'Content 2',
                ],
            ]);
        new NewsApiArticlesProvider(
            self::createMock(ArticleRepositoryInterface::class),
            self::createMock(SourceRepositoryInterface::class),
            $newsApiClient,
        )->fetchArticles();

        Queue::assertPushed(static function (SaveNewsApiArticleJob $job) {
            /** @phpstan-ignore-next-line */
            return $job->articleResponseDto == new ArticleResponseDto(
                source: new \App\Services\ArticleProviders\NewsApi\Dto\SourceResponseDto(
                    id: 'source1',
                    name: 'Source 1',
                ),
                author: 'Author 1',
                title: 'Title 1',
                description: 'Description 1',
                url: 'https://example.com/1',
                urlToImage: 'https://example.com/1.jpg',
                publishedAt: '2021-01-01T00:00:00Z',
                content: 'Content 1',
            );
        });
    }
}
