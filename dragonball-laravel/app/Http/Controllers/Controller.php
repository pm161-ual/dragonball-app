<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Dragon Ball API',
    version: '1.0.0',
    description: 'API para la aplicación de Dragon Ball',
)]

#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
)]

abstract class Controller
{
    //
}