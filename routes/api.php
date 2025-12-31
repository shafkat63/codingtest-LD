<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\ShortenUrlController;
use App\Http\Controllers\ShortenUrlController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/shorten', [ShortenUrlController::class, 'shorten']);
});

Route::get('/{shortCode}', [ShortenUrlController::class, 'redirectToOriginal']);
