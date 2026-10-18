<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Planet;
use OpenApi\Attributes as OA;

class PlanetController extends Controller
{
    #[OA\Get(
        path: '/api/planets',
        summary: 'Listar planetas',
        tags: ['Planetas'],
        parameters: [
            new OA\Parameter(name: 'name', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'is_destroyed', in: 'query', required: false, schema: new OA\Schema(type: 'boolean')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada de planetas'),
        ]
    )]

    public function index(Request $request)
    {

       $query = Planet::query();

       if ($request->filled('name')) {
           $query->where('name', 'like', '%' . $request->input('name') . '%');
       }

       if ($request->filled('is_destroyed')) {
           $query->where('is_destroyed', $request->boolean('is_destroyed'));
       }
       $perPage = $request->integer('per_page', 12);
       $perPage = min(100, $perPage);
       $perPage = max(1, $perPage);
       return $query->paginate($perPage)->withQueryString();
    }

    #[OA\Post(
    path: '/api/planets',
    summary: 'Crear un planeta',
    tags: ['Planetas'],
    security: [['sanctum' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'is_destroyed'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Vegeta'),
                new OA\Property(property: 'is_destroyed', type: 'boolean', example: true),
                new OA\Property(property: 'description', type: 'string', example: 'Planeta natal de los Saiyans'),
                new OA\Property(property: 'image', type: 'string', example: 'https://dragonball-api.com/planetas/Namek_U7.webp'
),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'Planeta creado'),
        new OA\Response(response: 401, description: 'No autenticado'),
        new OA\Response(response: 422, description: 'Datos no válidos'),
    ]
  )]

    public function store(Request $request)
    {
        $data = $request->validate([

            'name' => 'required|string|max:255',
            'is_destroyed' => 'required|boolean',
            'description' => 'required|string',
            'image' => 'required|url',
        ]);

        $planet = Planet::create($data);

        return response()->json($planet, 201);
    }

     #[OA\Get(
        path: '/api/planets/{planet}',
        summary: 'Ver un planeta',
        tags: ['Planetas'],
        parameters: [
            new OA\Parameter(name: 'planet', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Planeta encontrado'),
            new OA\Response(response: 404, description: 'Planeta no encontrado'),
        ]
    )]
    public function show(Planet $planet)
    {
        return $planet->load('characters');
    }

    #[OA\Put(
        path: '/api/planets/{planet}',
        summary: 'Actualizar un planeta',
        tags: ['Planetas'],
        security: [['sanctum' => []]],
        parameters: [
               new OA\Parameter(name: 'planet', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Vegeta'),
                    new OA\Property(property: 'is_destroyed', type: 'boolean', example: true),
                    new OA\Property(property: 'description', type: 'string', example: 'Planeta natal de los Saiyans'),
                    new OA\Property(property: 'image', type: 'string', example: 'https://dragonball-api.com/planetas/Namek_U7.webp'
    ),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Planeta actualizado'),
        new OA\Response(response: 401, description: 'No autenticado'),
        new OA\Response(response: 404, description: 'Planeta no encontrado'),
        new OA\Response(response: 422, description: 'Datos no válidos'),
    ]
    )]
    public function update(Request $request, Planet $planet)
    {
        $data = $request->validate([

            'name' => 'sometimes|required|string|max:255',
            'is_destroyed' => 'sometimes|boolean',
            'description' => 'sometimes|required|string',
            'image' => 'sometimes|required|url',
        ]);
        $planet->update($data);
        return $planet;
    }

    #[OA\Delete(
        path: '/api/planets/{planet}',
        summary: 'Eliminar planeta',
        tags: ['Planetas'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'planet', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Planeta eliminado'),
            new OA\Response(response: 401, description: 'No autenticado'),
            new OA\Response(response: 404, description: 'Planeta no encontrado'),
            new OA\Response(response: 409, description: 'No se puede eliminar un planeta que tiene personajes asignados'),
        ]
    )]
    public function destroy(Planet $planet)
    {
        if ($planet->characters()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar un planeta que tiene personajes asignados. Cámbialos de planeta primero.',
            ], 409);
        }
        $planet->delete();

        return response()->noContent();
    }
}
