<?php

declare(strict_types=1);

namespace App\UseCase\GetArticles;

use Domain\DTO\GetArticlesParameters;
use Domain\Exception\ExternalException;
use Domain\Repository\ArticleRepositoryInterface;
use Psr\Log\LoggerInterface;

class GetArticlesUseCase
{
    private const int DEFAULT_PER_PAGE = 10;

    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws ExternalException
     */
    public function __invoke(RequestModel $request): ResponseModel
    {
        try {
            $articlesPaginator = $this->articleRepository->getArticles(
                new GetArticlesParameters(
                    page: $request->page ?? 1,
                    perPage: $request->perPage ?? self::DEFAULT_PER_PAGE,
                    search: $request->search,
                    dateFrom: $request->dateFrom,
                    categoryId: $request->categoryId,
                    sourceId: $request->sourceId,
                )
            );
        } catch (\Throwable $exception) {
            $this->logger->error('Failed to get articles', [
                'exception' => $exception,
            ]);

            throw new ExternalException('Failed to get articles');
        }

        return new ResponseModel(
            paginator: $articlesPaginator,
        );
    }
}
