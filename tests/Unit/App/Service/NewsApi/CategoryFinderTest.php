<?php

declare(strict_types=1);

namespace Tests\Unit\App\Service\NewsApi;

use App\Services\ArticleProviders\NewsApi\CategoryFinder;
use App\Services\ArticleProviders\NewsApi\NewsApiClient;
use Domain\Entity\Category;
use Domain\Entity\Source;
use Domain\Exception\ExternalException;
use Domain\Repository\CategoryRepositoryInterface;
use PHPUnit\Framework\MockObject\Exception;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CategoryFinderTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ExternalException
     */
    public function testFindBySource(): void
    {
        $categoryRepo = self::createMock(CategoryRepositoryInterface::class);
        $expectedCategory = new Category(
            id: Uuid::uuid4(),
            code: 'business',
            name: 'Business',
        );
        $categoryRepo->method('findOrCreateByCode')->willReturn($expectedCategory);
        $newsApiClient = self::createMock(NewsApiClient::class);
        $newsApiClient->expects(self::once())
                      ->method('getCategoryCodeBySource')
                      ->willReturn('business');
        $categoryFinder = new CategoryFinder(
            categoryRepository: $categoryRepo,
            newsApiClient: $newsApiClient,
        );
        $category = $categoryFinder->findBySource(new Source(
            id: Uuid::uuid4(),
            code: 'source1',
            name: 'Source 1',
        ));
        $this->assertEquals('business', $category->code);
        $this->assertEquals('Business', $category->name);
    }
}
