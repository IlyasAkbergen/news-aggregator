<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Domain\Entity\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Article $resource
 */
class ArticleResource extends JsonResource
{
    public function __construct(Article $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id->toString(),
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'content' => $this->resource->content,
            'url' => (string) $this->resource->url,
            'imageUrl' => $this->resource->imageUrl ? (string) $this->resource->imageUrl : null,
            'author' => $this->resource->author !== null ? new AuthorResource($this->resource->author) : null,
            'source' => new SourceResource($this->resource->source),
            'category' => new CategoryResource($this->resource->category),
        ];
    }
}
