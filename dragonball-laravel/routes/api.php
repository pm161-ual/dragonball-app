<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlanetController;
use App\Http\Controllers\Api\CharacterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('planets', PlanetController::class); // Lo pongo para que me genere todas las rutas de la API para planetas

Route::apiResource('characters', CharacterController::class); // Lo pongo para que me genere todas las rutas de la API para personajes