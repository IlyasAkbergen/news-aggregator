<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

/**
 * @property string|null $page
 * @property string|null $per_page
 * @property string|null $search
 * @property string|null $date_from
 * @property string|null $category_id
 * @property string|null $source_id
 */
#[OA\Schema(
    title: 'GetArticlesRequest',
    required: ['page', 'per_page'],
    properties: [
        new OA\Property(property: 'page', type: 'integer'),
        new OA\Property(property: 'per_page', type: 'integer'),
        new OA\Property(property: 'search', type: 'string'),
        new OA\Property(property: 'date_from', type: 'string'),
        new OA\Property(property: 'category_id', type: 'string'),
        new OA\Property(property: 'source_id', type: 'string'),
    ],
    type: "object",
)]
class GetArticlesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:1', 'max:100'],
            'search' => ['string', 'max:30'],
            'date_from' => ['date'],
            'category_id' => ['string', 'uuid', 'exists:categories,id'],
            'source_id' => ['string', 'uuid', 'exists:sources,id'],
        ];
    }
}
