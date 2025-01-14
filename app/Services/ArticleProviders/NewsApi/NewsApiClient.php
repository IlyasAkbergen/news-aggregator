<?php

declare(strict_types=1);

namespace App\Services\ArticleProviders\NewsApi;

use DateTime;
use DateTimeImmutable;
use Domain\Entity\Source;
use Domain\Exception\ExternalException;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Cache;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

readonly class NewsApiClient
{
    public function __construct(
        private ClientInterface $httpClient,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @return array[]
     * @throws ExternalException
     */
    public function getArticles(
        array $sourceCodes,
        DateTimeImmutable $from,
    ): array {
        try {
            $response = json_decode(
                $this->httpClient->get('v2/everything', [
                    'query' => [
                        'sources' => implode(',', $sourceCodes),
                        'from' => $from->format(DateTime::RFC3339),
                    ],
                ])->getBody()->getContents(),
                true,
            );
        } catch (\Throwable $e) {
            $this->logger->error(
                sprintf('Failed to fetch articles from News API: %s', $e->getMessage()),
                [
                    'exception' => $e->getTraceAsString(),
                ],
            );

            throw new ExternalException('Failed to fetch articles from News API');
        }

        return $response['articles'];
    }

    /**
     * @throws ExternalException
     * @return Source[]
     */
    public function getSources(): array
    {
        $response = $this->getSourcesResponse();

        return array_map(
            static fn (array $source): Source => new Source(
                id: Uuid::uuid4(),
                code: (string) $source['id'],
                name: (string) $source['name'],
            ),
            $response['sources'],
        );
    }

    /**
     * @throws ExternalException
     */
    public function getCategoryCodeBySource(Source $source): string
    {
        $response = $this->getSourcesResponse();

        foreach ($response['sources'] as $sourceData) {
            if ($sourceData['id'] === $source->code) {
                return $sourceData['category'];
            }
        }

        throw new ExternalException(sprintf('Could not find source with code %s', $source->code));
    }

    /**
     * @throws ExternalException
     */
    private function getSourcesResponse(): array
    {
        return Cache::remember('news-api-sources', 24 * 3600, function (): array {
            try {
                $response = json_decode(
                    $this->httpClient->get('v2/sources')->getBody()->getContents(),
                    true,
                );
            } catch (\Throwable $e) {
                $this->logger->error(
                    sprintf('Failed to fetch sources from News API: %s', $e->getMessage()),
                    [
                        'exception' => $e->getTraceAsString(),
                    ],
                );

                throw new ExternalException('Failed to fetch sources from News API');
            }

            return $response;
        });
    }
}
