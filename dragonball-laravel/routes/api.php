<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlanetController;
use App\Http\Controllers\Api\CharacterController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'admin']);

Route::apiResource('planets', PlanetController::class)->only(['index', 'show']);
Route::apiResource('characters', CharacterController::class)->only(['index', 'show']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('planets', PlanetController::class)->except(['index', 'show']);
    Route::apiResource('characters', CharacterController::class)->except(['index', 'show']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verify'])->middleware('signed')->name('verification.verify');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('chat', [ChatController::class, 'chat'])->middleware(['auth:sanctum', 'throttle:10,1']);
Route::post('chat/stream', [ChatController::class, 'chatStream'])->middleware(['auth:sanctum', 'throttle:10,1']);