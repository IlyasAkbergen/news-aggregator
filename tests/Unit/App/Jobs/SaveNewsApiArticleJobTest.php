<?php

declare(strict_types=1);

namespace Tests\Unit\App\Jobs;

use App\Jobs\SaveNewsApiArticleJob;
use App\Services\ArticleProviders\NewsApi\Dto\ArticleResponseDto;
use App\Services\ArticleProviders\NewsApi\NewsApiClient;
use Domain\Entity\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\Exception;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class SaveNewsApiArticleJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws Exception
     */
    public function testItSavesArticle(): void
    {
        $newsApiClientMock = self::createMock(NewsApiClient::class);
        $newsApiClientMock
            ->expects(self::once())
            ->method('getSources')
            ->willReturn([
                $src1 = new Source(
                    id: Uuid::uuid4(),
                    code: 'test-id',
                    name: 'Test source',
                ),
                new Source(
                    id: Uuid::uuid4(),
                    code: 'test-id2',
                    name: 'Test source 2',
                ),
            ]);
        $newsApiClientMock
            ->expects(self::once())
            ->method('getCategoryCodeBySource')
            ->with($src1)
            ->willReturn('test');
        $this->app->instance(NewsApiClient::class, $newsApiClientMock);
        SaveNewsApiArticleJob::dispatch(
            ArticleResponseDto::fromPayload([
                'source' => [
                    'id' => 'test-id',
                    'name' => 'Test source',
                ],
                'author' => 'Test author',
                'title' => 'Test title',
                'description' => 'Test description',
                'url' => 'https://example.com',
                'urlToImage' => 'https://example.com/image.jpg',
                'publishedAt' => '2021-01-01T00:00:00Z',
                'content' => 'Test content',
            ]),
        );
        self::assertDatabaseHas('sources', [
            'code' => 'test-id',
        ]);
        self::assertDatabaseHas('authors', [
            'first_name' => 'Test',
            'last_name' => 'author',
        ]);
        self::assertDatabaseHas('categories', [
            'code' => 'test',
        ]);
        self::assertDatabaseHas('articles', [
            'title' => 'Test title',
            'description' => 'Test description',
            'url' => 'https://example.com',
            'image_url' => 'https://example.com/image.jpg',
            'published_at' => '2021-01-01 00:00:00',
            'content' => 'Test content',
        ]);
    }
}
