<?php

declare(strict_types=1);

namespace Tests\Unit\App\Service\NewsApi;

use App\Services\ArticleProviders\NewsApi\NewsApiClient;
use Domain\Entity\Source;
use Domain\Exception\ExternalException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\MockObject\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class NewsApiClientTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ExternalException
     */
    public function testGetEverything(): void
    {
        $expected = [
            'status' => 'ok',
            'totalResults' => 2,
            'articles' => [
                [
                    'source' => [
                        'id' => 'ynet',
                        'name' => 'Ynet',
                    ],
                    'author' => 'Iliyas Akbergen',
                    'title' => 'Some Title',
                    'description' => 'Some Description',
                    'url' => 'https://example.com',
                    'urlToImage' => 'https://example.com',
                    'publishedAt' => '2025-01-12T21:02:12Z',
                    'content' => 'Some Content',
                ],
                [
                    'source' => [
                        'id' => 'ynet',
                        'name' => 'Ynet',
                    ],
                    'author' => 'Iliyas Akbergen',
                    'title' => 'Some Title 2',
                    'description' => 'Some Description 2',
                    'url' => 'https://example.com',
                    'urlToImage' => 'https://example.com',
                    'publishedAt' => '2025-01-12T20:18:36Z',
                    'content' => 'Some Content 2',
                ],
            ],
        ];
        $bodyMock = self::createMock(StreamInterface::class);
        $bodyMock->expects(self::once())
            ->method('getContents')
            ->willReturn(json_encode($expected));
        $responseMock = self::createMock(ResponseInterface::class);
        $responseMock->expects(self::once())
            ->method('getBody')
            ->willReturn($bodyMock);
        $httpClientMock = self::createMock(Client::class);
        $httpClientMock->expects(self::once())
            ->method('request')
            ->with('GET', 'v2/everything', [
                'query' => [
                    'sources' => 'source1,source2',
                    'from' => '2025-01-01T12:34:56+00:00',
                ],
            ])
            ->willReturn($responseMock);
        $client = $this->getClient(
            httpClient: $httpClientMock,
        );
        $actual = $client->getArticles(['source1', 'source2'], new \DateTimeImmutable('2025-01-01T12:34:56Z'));

        self::assertEquals($expected['articles'], $actual);
    }

    /**
     * @throws Exception
     */
    public function testItHandlesErrorsOnGetArticles(): void
    {
        $httpClientMock = self::createMock(Client::class);
        $httpClientMock->expects(self::once())
            ->method('request')
            ->willThrowException(new \Exception('Some Error'));
        $loggerMock = self::createMock(LoggerInterface::class);
        $loggerMock->expects(self::once())->method('error');
        $client = $this->getClient(
            httpClient: $httpClientMock,
            logger: $loggerMock,
        );

        $this->expectException(ExternalException::class);
        $this->expectExceptionMessage('Failed to fetch articles from News API');
        $client->getArticles(['source1', 'source2'], new \DateTimeImmutable('2025-01-01T12:34:56Z'));
    }

    /**
     * @throws ExternalException
     * @throws Exception
     */
    public function testGetSources(): void
    {
        $expected = [
            'status' => 'ok',
            'sources' => [
                [
                    'id' => 'abc-news',
                    'name' => 'ABC News',
                    'description' => 'Your trusted source for breaking news, analysis, exclusive interviews, headlines, and videos at ABCNews.com.',
                    'url' => 'https://abcnews.go.com',
                    'category' => 'general',
                    'language' => 'en',
                    'country' => 'us',
                ],
                [
                    'id' => 'four-four-two',
                    'name' => 'FourFourTwo',
                    'description' => 'The latest football news, in-depth features, tactical and statistical analysis from FourFourTwo, the UK&#039;s favourite football monthly.',
                    'url' => 'http://www.fourfourtwo.com/news',
                    'category' => 'sports',
                    'language' => 'en',
                    'country' => 'gb',
                ],
            ],
        ];
        $bodyMock = self::createMock(StreamInterface::class);
        $bodyMock->expects(self::once())
            ->method('getContents')
            ->willReturn(json_encode($expected));
        $responseMock = self::createMock(ResponseInterface::class);
        $responseMock->expects(self::once())
            ->method('getBody')
            ->willReturn($bodyMock);
        $httpClientMock = self::createMock(Client::class);
        $httpClientMock->expects(self::once())
            ->method('request')
            ->with('GET', 'v2/sources')
            ->willReturn($responseMock);
        $client = $this->getClient(
            httpClient: $httpClientMock,
        );
        $actual = $client->getSources();

        self::assertCount(2, $actual);
        self::assertEquals('abc-news', $actual[0]->code);
        self::assertEquals('ABC News', $actual[0]->name);
        self::assertEquals('four-four-two', $actual[1]->code);
        self::assertEquals('FourFourTwo', $actual[1]->name);
    }

    /**
     * @throws Exception
     * @throws ExternalException
     */
    public function testGetCategoryByCode(): void
    {
        $bodyMock = self::createMock(StreamInterface::class);
        $bodyMock->expects(self::once())
            ->method('getContents')
            ->willReturn(json_encode([
                'status' => 'ok',
                'sources' => [
                    [
                        'id' => 'abc-news',
                        'name' => 'ABC News',
                        'description' => 'Your trusted source for breaking news, analysis, exclusive interviews, headlines, and videos at ABCNews.com.',
                        'url' => 'https://abcnews.go.com',
                        'category' => 'general',
                        'language' => 'en',
                        'country' => 'us',
                    ],
                    [
                        'id' => 'four-four-two',
                        'name' => 'FourFourTwo',
                        'description' => 'The latest football news, in-depth features, tactical and statistical analysis from FourFourTwo, the UK&#039;s favourite football monthly.',
                        'url' => 'http://www.fourfourtwo.com/news',
                        'category' => 'sports',
                        'language' => 'en',
                        'country' => 'gb',
                    ],
                ],
            ]));
        $responseMock = self::createMock(ResponseInterface::class);
        $responseMock->expects(self::once())
                     ->method('getBody')
                     ->willReturn($bodyMock);
        $httpClientMock = self::createMock(Client::class);
        $httpClientMock->expects(self::once())
                       ->method('request')
                       ->with('GET', 'v2/sources')
                       ->willReturn($responseMock);
        $client = $this->getClient(
            httpClient: $httpClientMock,
        );

        self::assertEquals(
            'general',
            $client->getCategoryCodeBySource(new Source(
                id: Uuid::uuid4(),
                code: 'abc-news',
                name: 'ABC News',
            )),
        );
        self::assertEquals(
            'sports',
            $client->getCategoryCodeBySource(new Source(
                id: Uuid::uuid4(),
                code: 'four-four-two',
                name: 'FourFourTwo',
            )),
        );
    }

    /**
     * @throws Exception
     */
    private function getClient(
        ?ClientInterface $httpClient = null,
        ?LoggerInterface $logger = null,
    ): NewsApiClient {
        return new NewsApiClient(
            $httpClient ?? self::createMock(ClientInterface::class),
            $logger ?? self::createMock(LoggerInterface::class),
        );
    }
}
