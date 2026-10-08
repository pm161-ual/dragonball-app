<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlanetController;
use App\Http\Controllers\Api\CharacterController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Zona publica donde cualquiera puede ver los planetas y personajes
Route::apiResource('planets', PlanetController::class)->only(['index', 'show']); // Lo pongo para que me genere todas las rutas de la API para planetas
Route::apiResource('characters', CharacterController::class)->only(['index', 'show']); // Lo pongo para que me genere todas las rutas de la API para personajes

//Zona de administracion donde solo los qu tienen el token pueden acceder
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('planets', PlanetController::class)->except(['index', 'show']); // Lo pongo para que me genere todas las rutas de la API para planetas
    Route::apiResource('characters', CharacterController::class)->except(['index', 'show']); // Lo pongo para que me genere todas las rutas de la API para personajes
});

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
