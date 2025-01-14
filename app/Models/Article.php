<?php

declare(strict_types=1);

namespace App\Models;

use Domain\Enum\ArticleProviderCode;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $url
 * @property string|null $image_url
 * @property Carbon $published_at
 * @property Source $source
 * @property Author|null $author
 * @property Category $category
 * @property ArticleProviderCode $provider_code
 */
class Article extends Model
{
    use HasTimestamps;
    use HasUuids;

    protected $fillable = [
        'id',
        'title',
        'description',
        'content',
        'url',
        'image_url',
        'author_id',
        'source_id',
        'category_id',
        'published_at',
        'provider_code',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'provider_code' => ArticleProviderCode::class,
    ];

    public function getId(): UuidInterface
    {
        return $this->id instanceof UuidInterface ? $this->id : Uuid::fromString($this->id);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
