<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Character;
use OpenApi\Attributes as OA;

class CharacterController extends Controller
{
    #[OA\Get(
            path: '/api/characters',
            summary: 'Listar personajes',
            tags: ['Personajes'],
            parameters: [
                new OA\Parameter(name: 'name', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
                new OA\Parameter(name: 'race', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
                new OA\Parameter(name: 'gender', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
                new OA\Parameter(name: 'affiliation', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
                new OA\Parameter(name: 'planet_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
                new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            ],
            responses: [
                new OA\Response(response: 200, description: 'Lista paginada de personajes'),
            ]
        )]
    public function index(Request $request)
    {
        $query = Character::with('planet');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('race')) {                      
           $query->where('race', $request->input('race'));   
        }

        if ($request->filled('gender')) {                      
            $query->where('gender', $request->input('gender'));   
        }

        if ($request->filled('affiliation')) {                      
            $query->where('affiliation', $request->input('affiliation'));   
        }

        if ($request->filled('planet_id')) {                      
            $query->where('planet_id', $request->input('planet_id'));   
        }

        return $query->paginate(10)->withQueryString();
    }

    #[OA\Post(
        path: '/api/characters',
        summary: 'Crear un personaje',
        tags: ['Personajes'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Goku'),
                    new OA\Property(property: 'ki', type: 'string', example: '60.000.000'),
                    new OA\Property(property: 'max_ki', type: 'string', example: '90 Septillion'),
                    new OA\Property(property: 'race', type: 'string', example: 'Saiyan'),
                    new OA\Property(property: 'gender', type: 'string', example: 'Male'),
                    new OA\Property(property: 'affiliation', type: 'string', example: 'Z Fighter'),
                    new OA\Property(property: 'description', type: 'string', example: 'Protagonista de la serie'),
                    new OA\Property(property: 'image', type: 'string', example: 'https://dragonball-api.com/characters/goku_normal.webp'),
                    new OA\Property(property: 'planet_id', type: 'integer', example: 2),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Personaje creado'),
            new OA\Response(response: 401, description: 'No autenticado'),
            new OA\Response(response: 422, description: 'Datos no válidos o el planeta no existe'),
        ]
    )]
    public function store(Request $request)
    {
        $data = $request->validate([ // Valida los datos y guarda en $data solo los campos de la lista (si algo falla, responde 422)

            'name' => 'required|string|max:255',
            'ki' => 'nullable|string|max:50',
            'max_ki' => 'nullable|string|max:50',
            'race' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'affiliation' => 'nullable|string|max:100',
            'planet_id' => 'nullable|exists:planets,id', // Verifica que el planeta exista en la tabla planets
        ]);

        $character = Character::create($data);

        return response()->json($character->load('planet'), 201); // Devuelve el personaje creado con su planeta asociado
    }

    #[OA\Get(
        path: '/api/characters/{character}',
        summary: 'Ver un personaje',
        tags: ['Personajes'],
        parameters: [
            new OA\Parameter(name: 'character', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Personaje encontrado'),
            new OA\Response(response: 404, description: 'Personaje no encontrado'),
        ]   
    )]
    public function show(Character $character) // Pongo Character $character para no estar buscando el id sino que Laravel me lo busque automaticamente y me lo pase como objeto
    {
        return $character->load('planet'); // Esto me devuelve el personaje que le paso por id
        
    }

 #[OA\Put(
        path: '/api/characters/{character}',
        summary: 'Actualizar un personaje',
        tags: ['Personajes'],
        security: [['sanctum' => []]],
        parameters: [
               new OA\Parameter(name: 'character', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                   new OA\Property(property: 'name', type: 'string', example: 'Goku'),
                   new OA\Property(property: 'ki', type: 'string', example: '60.000.000'),
                   new OA\Property(property: 'max_ki', type: 'string', example: '90 Septillion'),
                   new OA\Property(property: 'race', type: 'string', example: 'Saiyan'),
                   new OA\Property(property: 'gender', type: 'string', example: 'Male'),
                   new OA\Property(property: 'affiliation', type: 'string', example: 'Z Fighter'),
                   new OA\Property(property: 'description', type: 'string', example: 'Protagonista de la serie'),
                   new OA\Property(property: 'image', type: 'string', example: 'https://dragonball-api.com/characters/goku_normal.webp'),
                   new OA\Property(property: 'planet_id', type: 'integer', example: 1),
                ]
            )
  ),
    responses: [
        new OA\Response(response: 200, description: 'Personaje actualizado'),
        new OA\Response(response: 401, description: 'No autenticado'),
        new OA\Response(response: 404, description: 'Personaje no encontrado'),
        new OA\Response(response: 422, description: 'Datos no válidos o el planeta no existe'),
    ]
    )]
    public function update(Request $request, Character $character)
   {
        $data = $request->validate([ // Valida los datos y guarda en $data solo los campos de la lista (si algo falla, responde 422)

            'name' => 'sometimes|string|max:255',
            'ki' => 'nullable|string|max:50',
            'max_ki' => 'nullable|string|max:50',
            'race' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'affiliation' => 'nullable|string|max:100',
            'planet_id' => 'nullable|exists:planets,id', // Verifica que el planeta exista en la tabla planets
        ]);

        $character->update($data);

        return response()->json($character->load('planet'), 200); // Devuelve el personaje actualizado con su planeta asociado
    }

    #[OA\Delete(
        path: '/api/characters/{character}',
        summary: 'Eliminar personaje',
        tags: ['Personajes'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'character', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Personaje eliminado'),
            new OA\Response(response: 401, description: 'No autenticado'),
            new OA\Response(response: 404, description: 'Personaje no encontrado'),
        ]   
    )]
    public function destroy(Character $character) // Pongo Character $character para no estar buscando el id sino que Laravel me lo busque automaticamente y me lo pase como objeto
    {
        $character->delete(); // Esto me borra el personaje que le paso por id
        return response()->noContent(); 
    }
}
