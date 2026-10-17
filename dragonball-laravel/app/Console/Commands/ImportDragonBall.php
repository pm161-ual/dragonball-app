<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Planet;
use App\Models\Character;

class ImportDragonBall extends Command
{
    protected $signature = 'dragonball:import';

    protected $description = 'El mundo de Dragon Ball.';

    public function handle()
    {
        $this->info('Importando datos de Dragon Ball...');
        $response = Http::get('https://dragonball-api.com/api/planets', ['limit' => 100]);
        $planets = $response->json('items');

        foreach ($planets as $item) {
            Planet::updateOrCreate([
                'external_id' => $item['id'],
            ], [
                'name' => $item['name'],
                'is_destroyed' => $item['isDestroyed'],
                'description' => $item['description'],
                'image' => $item['image'],
            ]
            );
        }
        $this->info('Planetas importados: ' . count($planets));

        $characters = Http::get('https://dragonball-api.com/api/characters', ['limit' => 100])->json("items");

        foreach ($characters as $item) {
           $detail = Http::get('https://dragonball-api.com/api/characters/' . $item['id'])->json();

           $planetId = null;
           if (!empty($detail['originPlanet'])) {
               $planetId = Planet::where('external_id', $detail['originPlanet']['id'])->value('id');
           }

           Character::updateOrCreate([
                'external_id' => $item['id'],
                ], [
                    'name' => $item['name'],
                    'ki' => $item['ki'],
                    'max_ki' => $item['maxKi'],
                    'race' => $item['race'],
                    'gender' => $item['gender'],
                    'description' => $item['description'],
                    'image' => $item['image'],
                    'affiliation' => $item['affiliation'],
                    'planet_id' => $planetId,
                ]
            );
        }
        $this->info('Personajes importados: ' . count($characters));
    }
}
