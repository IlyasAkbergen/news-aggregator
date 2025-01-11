<?php

declare(strict_types=1);

use App\Jobs\FetchArticles;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schedule;

//Schedule::job(new FetchArticles(App::get(NewsAPIArticlesProvider::class)))->everyFiveMinutes();
