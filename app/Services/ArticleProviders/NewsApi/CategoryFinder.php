<?php

declare(strict_types=1);

namespace App\Services\ArticleProviders\NewsApi;

use Domain\Entity\Category;
use Domain\Entity\Source;
use Domain\Exception\ExternalException;
use Domain\Repository\CategoryRepositoryInterface;

readonly class CategoryFinder
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private NewsApiClient $newsApiClient,
    ) {
    }

    /**
     * @throws ExternalException
     */
    public function findBySource(Source $source): Category
    {
        $categoryCode = $this->newsApiClient->getCategoryCodeBySource($source);

        return $this->categoryRepository->findOrCreateByCode($categoryCode);
    }
}
