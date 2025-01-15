<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\GetArticlesRequest;
use App\Http\Resources\ArticleResource;
use App\UseCase\GetArticles\GetArticlesUseCase;
use App\UseCase\GetArticles\RequestModel;
use DateMalformedStringException;
use DateTimeImmutable;
use Domain\Exception\ExternalException;
use Domain\Repository\ArticleRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Ramsey\Uuid\Uuid;
use OpenApi\Attributes as OA;

class ArticlesController extends BaseApiController
{
    /**
     * @throws DateMalformedStringException
     * @throws ExternalException
     */
    #[OA\PathItem(
        path: '/api/articles',
        get: new OA\Get(
            security: [['sanctum' => []]],
            tags: [ 'Articles'],
            parameters: [
                new OA\Parameter(name: 'page', in: 'query', required: true),
                new OA\Parameter(name: 'per_page', in: 'query', required: true),
                new OA\Parameter(name: 'search', in: 'query'),
                new OA\Parameter(name: 'date_from', in: 'query'),
                new OA\Parameter(name: 'category_id', in: 'query'),
                new OA\Parameter(name: 'source_id', in: 'query'),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Successful operation',
                    content: new OA\MediaType(
                        mediaType: 'application/json',
                        schema: new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: 'data',
                                    properties: [
                                        new OA\Property(
                                            property: 'meta',
                                            properties: [],
                                            type: 'object',
                                        ),
                                        new OA\Property(
                                            property: 'records',
                                            type: 'array',
                                            items: new OA\Items(
                                                ref: '#/components/schemas/ArticleResource',
                                            ),
                                        ),
                                    ],
                                    type: 'object',
                                ),
                            ],
                            type: 'object',
                        ),
                    ),
                ),
            ],
        ),
    )]
    public function index(GetArticlesRequest $request, GetArticlesUseCase $useCase): JsonResource
    {
        $responseModel = $useCase(new RequestModel(
            page: $request->input('page') !== null ? (int) $request->input('page') : null,
            perPage: $request->input('per_page') !== null ? (int) $request->input('per_page') : null,
            search: $request->input('search'),
            dateFrom: $request->input('date_from') ? new DateTimeImmutable($request->input('date_from')) : null,
            categoryId: $request->input('category_id') !== null
                ? Uuid::fromString($request->input('category_id'))
                : null,
            sourceId: $request->input('source_id') !== null
                ? Uuid::fromString($request->input('source_id'))
                : null,
        ));

        return new JsonResource([
            'meta' => [
                'total' => $responseModel->paginator->total(),
                'page' => $responseModel->paginator->currentPage(),
                'per_page' => $responseModel->paginator->perPage(),
                'last_page' => $responseModel->paginator->lastPage(),
            ],
            'records' => ArticleResource::collection($responseModel->paginator->items()),
        ]);
    }

    #[OA\PathItem(
        path: '/api/articles/{id}',
        get: new OA\Get(
            security: [['sanctum' => []]],
            tags: [ 'Articles'],
            parameters: [
                new OA\Parameter(name: 'id', in: 'path', required: true),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Successful operation',
                    content: new OA\MediaType(
                        mediaType: 'application/json',
                        schema: new OA\Schema(ref: '#/components/schemas/ArticleResource'),
                    ),
                ),
            ],
        ),
    )]
    public function show(string $id, ArticleRepositoryInterface $articleRepository): JsonResource
    {
        $article = $articleRepository->find(Uuid::fromString($id));

        if ($article === null) {
            abort(404, 'Article not found');
        }

        return new ArticleResource($article);
    }
}
