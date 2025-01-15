<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Domain\Entity\Author;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Author $resource
 */
class AuthorResource extends JsonResource
{
    public function __construct(Author $resource)
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
            'full_name' => (string) $this->resource->fullName,
        ];
    }
}
