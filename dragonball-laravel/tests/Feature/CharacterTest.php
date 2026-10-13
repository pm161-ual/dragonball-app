<?php

namespace Tests\Feature;

use App\Models\Character;
use App\Models\Planet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CharacterTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_ver_personaje_trae_su_planeta(): void
    {
        $planet = Planet::factory()->create(['name' => 'Namek']);
        $character = Character::factory()->create(['planet_id' => $planet->id]);

        $response = $this->getJson('/api/characters/' . $character->id);

        $response->assertStatus(200);
        $response->assertJsonPath('planet.name', 'Namek');
    }

    
    public function test_cambiar_personaje_de_planeta(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $tierra = Planet::factory()->create();
        $namek = Planet::factory()->create();
        $character = Character::factory()->create(['planet_id' => $tierra->id]);

        $response = $this->putJson('/api/characters/' . $character->id, [
            'planet_id' => $namek->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('characters', [
            'id' => $character->id,
            'planet_id' => $namek->id,
        ]);
    }
}
