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
    public function index()
    {
        return Character::with('planet')->get(); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
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
