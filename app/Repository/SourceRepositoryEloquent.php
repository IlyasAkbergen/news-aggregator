<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Source as SourceModel;
use Domain\Entity\Source;
use Domain\Repository\SourceRepositoryInterface;

class SourceRepositoryEloquent implements SourceRepositoryInterface
{
    public function getSources(): array
    {
        return SourceModel::all()
            ->map(
                static fn (SourceModel $sourceModel) => new Source(
                    id: $sourceModel->getId(),
                    code: $sourceModel->code,
                    name: $sourceModel->name,
                )
            )
            ->toArray();
    }

    public function save(Source ...$sources): void
    {
        SourceModel::query()->upsert(
            array_map(
                static fn (Source $source) => [
                    'id' => $source->id,
                    'code' => $source->code,
                    'name' => $source->name,
                ],
                $sources,
            ),
            uniqueBy: 'code',
            update: ['name'],
        );
    }

    public function findByCode(string $code): ?Source
    {
        $record = SourceModel::query()
            ->where('code', $code)
            ->first();

        if ($record === null) {
            return null;
        }

        return new Source(
            id: $record->getId(),
            code: $record->code,
            name: $record->name,
        );
    }
}
