<?php

declare(strict_types=1);

namespace App\UseCase\GetArticles;

use Domain\Service\PaginatorInterface;

class ResponseModel
{
    public function __construct(
        public PaginatorInterface $paginator,
    ) {
    }
}
