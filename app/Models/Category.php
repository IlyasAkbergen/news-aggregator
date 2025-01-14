<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @property string $id
 * @property string $code
 * @property string $name
 */
class Category extends Model
{
    use HasTimestamps;
    use HasUuids;

    protected $fillable = [
        'id',
        'code',
        'name',
    ];

    public function getId(): UuidInterface
    {
        return $this->id instanceof UuidInterface ? $this->id : Uuid::fromString($this->id);
    }
}
