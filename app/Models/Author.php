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
 * @property string $first_name
 * @property string $last_name
 */
class Author extends Model
{
    use HasTimestamps;
    use HasUuids;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
    ];

    public static function createFromEntity(\Domain\Entity\Author $author): self
    {
        return new self([
            'first_name' => $author->fullName->firstName,
            'last_name' => $author->fullName->lastName,
        ]);
    }

    public function getId(): UuidInterface
    {
        return $this->id instanceof UuidInterface ? $this->id : Uuid::fromString($this->id);
    }
}
