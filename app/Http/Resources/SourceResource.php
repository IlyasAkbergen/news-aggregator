<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Domain\Entity\Source;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Source $resource
 */
class SourceResource extends JsonResource
{
    public function __construct(Source $resource)
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
            'code' => $this->resource->code,
            'name' => $this->resource->name,
        ];
    }
}
