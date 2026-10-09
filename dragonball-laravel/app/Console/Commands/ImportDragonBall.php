<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Planet;
use App\Models\Character;


class ImportDragonBall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dragonball:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'El mundo de Dragon Ball.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Importando datos de Dragon Ball...'); // Muestra un mensaje en la consola indicando que se está importando datos de Dragon Ball
        $response = Http::get('https://dragonball-api.com/api/planets', ['limit' => 100]); // La URL hace una llamada a la API que tiene los datos de Dragon Ball.
        $planets = $response->json('items'); // La API que tiene los datos le devuelve todos los datos a la URL de los planetas.

        foreach ($planets as $item) { // Recorre todos los planetas que le devuelve la API y los guarda en la base de datos. Si el planeta ya existe, lo actualiza, si no existe, lo crea. 
            Planet::updateOrCreate([
                'external_id' => $item['id'], // Busca un planeta en la base de datos con el mismo ID externo que el del elemento actual
            ], [
                'name' => $item['name'], // Si no existe, crea un nuevo registro
                'is_destroyed' => $item['isDestroyed'],
                'description' => $item['description'],
                'image' => $item['image'],
            ]
            );
        }
        $this->info('Planetas importados: ' . count($planets)); // Muestra un mensaje en la consola indicando cuántos planetas se han importado

        $characters = Http::get('https://dragonball-api.com/api/characters', ['limit' => 100])->json("items"); // La URL hace una llamada a la API que tiene los datos de Dragon Ball.
       
        foreach ($characters as $item) {
           $detail = Http::get('https://dragonball-api.com/api/characters/' . $item['id'])->json(); // La URL hace una llamada a la API que tiene los datos de Dragon Ball.

           $planetId = null; // Inicializa la variable $planetId como null
           if (!empty($detail['originPlanet'])) { // Si el planeta de origen no está vacío, busca el planeta en la base de datos y obtiene su ID
               $planetId = Planet::where('external_id', $detail['originPlanet']['id'])->value('id');  
           }

           Character::updateOrCreate([
                'external_id' => $item['id'], // Busca un personaje en la base de datos con el mismo ID externo que el del elemento actual
                ], [
                    'name' => $item['name'], // Si no existe, crea un nuevo registro
                    'ki' => $item['ki'],
                    'max_ki' => $item['maxKi'],
                    'race' => $item['race'],
                    'gender' => $item['gender'],
                    'description' => $item['description'],
                    'image' => $item['image'],
                    'affiliation' => $item['affiliation'],
                    'planet_id' => $planetId, // Asigna el ID del planeta de origen al personaje
                ]
            );
        }
        $this->info('Personajes importados: ' . count($characters));
    }
}
