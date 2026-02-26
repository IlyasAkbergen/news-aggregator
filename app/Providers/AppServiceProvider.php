<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repository\ArticleRepositoryEloquent;
use App\Repository\AuthorRepositoryEloquent;
use App\Repository\CategoryRepositoryEloquent;
use App\Repository\SourceRepositoryEloquent;
use App\Services\ArticleProviders\NewsApi\NewsApiArticlesProvider;
use App\Services\ArticleProviders\NewsApi\NewsApiClient;
use Carbon\Carbon;
use Domain\Repository\ArticleRepositoryInterface;
use Domain\Repository\AuthorRepositoryInterface;
use Domain\Repository\CategoryRepositoryInterface;
use Domain\Repository\SourceRepositoryInterface;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepositoryEloquent::class);
        $this->app->bind(SourceRepositoryInterface::class, SourceRepositoryEloquent::class);
        $this->app->bind(AuthorRepositoryInterface::class, AuthorRepositoryEloquent::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepositoryEloquent::class);

        $this->app->tag([
            NewsApiArticlesProvider::class,
        ], 'articlesProviders');

        $this->app->when(NewsApiClient::class)
            ->needs(ClientInterface::class)
            ->give(function () {
                return new \GuzzleHttp\Client([
                    'base_uri' => config('services.newsapi.base_url'),
                    'headers' => [
                        'X-Api-Key' => config('services.newsapi.api_key'),
                        'Accept' => 'application/json',
                    ],
                    'query' => [
                        'apiKey' => config('services.newsapi.api_key'),
                    ],
                ]);
            });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
