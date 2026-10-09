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
       // Esta funcion me devuelve todos los planetas paginados de 10 en 10 y si le paso un parametro name me filtra por nombre. 
        //Uso withQueryString() para que los enlaces de paginación conserven los filtros activos

       $query = Planet::query();

       if ($request->filled('name')) {
           $query->where('name', 'like', '%' . $request->input('name') . '%');
       }

       if ($request->filled('is_destroyed')) {
           $query->where('is_destroyed', $request->boolean('is_destroyed'));
       }

       return $query->paginate(10)->withQueryString(); // Esto me devuelve todos los planetas paginados de 10 en 10 y si le paso un parametro name me filtra por nombre
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
        $data = $request->validate([ // Valida los datos y guarda en $data solo los campos de la lista (si algo falla, responde 422)

            'name' => 'required|string|max:255',
            'is_destroyed' => 'required|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
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
    public function show(Planet $planet) // Pongo Planet $planet para no estar buscando el id sino que Laravel me lo busque automaticamente y me lo pase como objeto
    {
        return $planet; // Esto me devuelve el planeta que le paso por id
    }
   
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Planet $planet)
    {
        $data = $request->validate([ // Valida los datos y guarda en $data solo los campos de la lista (si algo falla, responde 422)

            'name' => 'sometimes|string|max:255', // es sometimes porque no es obligatorio que venga en la request, si no viene no lo valida y no lo actualiza
            'is_destroyed' => 'sometimes|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
        ]);
        $planet->update($data); // Esto me actualiza el planeta que le paso por id con los datos que le paso en la request
        return $planet; // Esto me devuelve el planeta que le paso por id al actualizarse
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Planet $planet) // Pongo Planet $planet para no estar buscando el id sino que Laravel me lo busque automaticamente y me lo pase como objeto
    {
        $planet->delete(); // Esto me borra el planeta que le paso por id
        return response()->noContent(); // Esto me borra el planeta que le paso por id
    }
}
