<?php

declare(strict_types=1);

namespace App\Jobs;

use Domain\Service\ArticlesProvider;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FetchArticles implements ShouldQueue
{
    use Queueable;

    public function __construct(
        readonly private ArticlesProvider $articlesProvider,
    ) {
    }

    public function handle(): void
    {
        $this->articlesProvider->fetchArticles();
    }
}
