<?php

use App\Jobs\FetchArticlesJob;
use App\Services\ArticleProviders\NewsApi\NewsApiArticlesProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('test', function () {
    new FetchArticlesJob(App::get(NewsApiArticlesProvider::class))->handle();
});
