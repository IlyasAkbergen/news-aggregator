<?php

declare(strict_types=1);

namespace App\Services;

use Closure;
use Domain\Service\PaginatorInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class LaravelPaginatorAdapter implements PaginatorInterface
{
    public function __construct(
        public LengthAwarePaginator $paginator,
        private ?Closure $itemsTransformer = null,
    ) {
    }

    public function total(): int
    {
        return $this->paginator->total();
    }

    public function lastPage(): int
    {
        return $this->paginator->lastPage();
    }

    public function currentPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function limit(): int
    {
        return $this->paginator->perPage();
    }

    public function perPage(): int
    {
        return $this->paginator->perPage();
    }

    public function items(): array
    {
        return $this->itemsTransformer !== null
            ? array_map($this->itemsTransformer, $this->paginator->items())
            : $this->paginator->items();
    }
}
