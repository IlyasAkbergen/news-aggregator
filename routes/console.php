<?php

declare(strict_types=1);

use App\Jobs\FetchArticlesJob;
use App\Services\ArticleProviders\NewsApi\NewsApiArticlesProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new FetchArticlesJob(App::get(NewsAPIArticlesProvider::class)))->everyThirtyMinutes();
