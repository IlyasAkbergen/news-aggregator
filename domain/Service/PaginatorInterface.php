<?php

declare(strict_types=1);

namespace Domain\Service;

interface PaginatorInterface
{
    public function total(): int;

    public function lastPage(): int;

    public function currentPage(): int;

    public function limit(): int;

    public function perPage(): int;

    public function items(): array;
}
