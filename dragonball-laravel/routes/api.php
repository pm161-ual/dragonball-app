<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlanetController;
use App\Http\Controllers\Api\CharacterController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('planets', PlanetController::class)->only(['index', 'show']);
Route::apiResource('characters', CharacterController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('planets', PlanetController::class)->except(['index', 'show']);
    Route::apiResource('characters', CharacterController::class)->except(['index', 'show']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
