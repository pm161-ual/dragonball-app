<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Character;

class CharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Character $character) // Pongo Character $character para no estar buscando el id sino que Laravel me lo busque automaticamente y me lo pase como objeto
    {
        return $character->load('planet'); // Esto me devuelve el personaje que le paso por id
        
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Character $character) // Pongo Character $character para no estar buscando el id sino que Laravel me lo busque automaticamente y me lo pase como objeto
    {
        $character->delete(); // Esto me borra el personaje que le paso por id
        return response()->noContent(); 
    }
}
