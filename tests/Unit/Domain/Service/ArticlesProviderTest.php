<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Service;

use Domain\Entity\Article;
use Domain\Repository\ArticleRepositoryInterface;
use Domain\Service\ArticlesProvider;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class ArticlesProviderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testFetchArticles(): void
    {
        $articles = [
            self::createMock(Article::class),
            self::createMock(Article::class),
        ];
        $articleRepository = self::createMock(ArticleRepositoryInterface::class);
        $articleRepository->expects(self::once())
            ->method('save')
            ->with(...$articles);

        $articlesProvider = new class($articles, $articleRepository) extends ArticlesProvider {
            /**
             * @param Article[] $articles
             */
            public function __construct(
                private $articles,
                ArticleRepositoryInterface $articleRepository,
            ) {
                parent::__construct($articleRepository);
            }

            protected function getNewArticles(): array
            {
                return $this->articles;
            }
        };

        $articlesProvider->fetchArticles();
    }
}
