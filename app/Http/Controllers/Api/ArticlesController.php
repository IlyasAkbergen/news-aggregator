<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetArticlesRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\UseCase\GetArticles\GetArticlesUseCase;
use App\UseCase\GetArticles\RequestModel;
use DateMalformedStringException;
use DateTimeImmutable;
use Domain\Exception\ExternalException;
use Domain\Repository\ArticleRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Ramsey\Uuid\Uuid;

class ArticlesController extends Controller
{
    /**
     * @throws DateMalformedStringException
     * @throws ExternalException
     */
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

    public function show(string $id, ArticleRepositoryInterface $articleRepository): JsonResource
    {
        $article = $articleRepository->find(Uuid::fromString($id));

        if ($article === null) {
            abort(404, 'Article not found');
        }

        return new ArticleResource($article);
    }
}
