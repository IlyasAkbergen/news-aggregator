<?php

use App\Http\Controllers\Api\ArticlesController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('articles', [ArticlesController::class, 'index'])->middleware('auth:sanctum');

Route::apiResource('articles', ArticlesController::class)
    ->only(['index', 'show'])
    ->middleware('auth:sanctum');
