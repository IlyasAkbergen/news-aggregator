<?php

declare(strict_types=1);

namespace Tests\Unit\App\Service\NewsApi;

use App\Services\ArticleProviders\NewsApi\NewsApiArticlesProvider;
use App\Services\ArticleProviders\NewsApi\SourceFinder;
use Domain\Entity\Source;
use Domain\Exception\ExternalException;
use Domain\Repository\SourceRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class SourceFinderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws Exception
     * @throws ExternalException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testFindSources(): void
    {
        $articlesProviderMock = self::createMock(NewsApiArticlesProvider::class);
        $articlesProviderMock->expects(self::once())
         ->method('getSources')
         ->willReturn([
             new Source(
                 id: Uuid::uuid4(),
                 code: 'source1',
                 name: 'Source 1',
             ),
         ]);
        $sourceFinder = new SourceFinder(
            sourceRepository: $this->app->get(SourceRepositoryInterface::class),
            articlesProvider: $articlesProviderMock,
        );
        $source = $sourceFinder->findSource('source1');

        self::assertEquals('source1', $source->code);
        self::assertDatabaseHas('sources', ['code' => 'source1']);
    }
}
