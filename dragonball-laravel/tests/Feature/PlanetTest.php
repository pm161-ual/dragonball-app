<?php

namespace Tests\Feature;

use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class PlanetTest extends TestCase
{
    use RefreshDatabase;

    public function test_listar_planetas(): void
    {
        Planet::factory()->count(3)->create();

        $response = $this->getJson('/api/planets');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }
    public function test_crear_planeta_sin_token(): void
    {
        $response = $this->postJson('/api/planets' , [
            'name' => 'Vegeta',
            'is_destroyed' => true,

        ]);

        $response->assertStatus(401);
    }

    public function test_crear_planeta_con_token(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/planets', [
            'name' => 'Vegeta',
            'is_destroyed' => true,
            'description' => 'Planeta de los saiyans',
            'image' => 'https://example.com/vegeta.png'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('planets', ['name' => 'Vegeta']);
    }

    public function test_ver_planeta_que_no_existe_devuelve_404(): void
    {
        $response = $this->getJson('/api/planets/999');

        $response->assertStatus(404);
    }

    public function test_borrar_planeta(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $planet = Planet::factory()->create();

        $response = $this->deleteJson('/api/planets/' . $planet->id);

        $response->assertStatus(204);
        $this->assertSoftDeleted($planet);
    }

    public function test_crear_planeta_sin_nombre(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/planets', [
            'is_destroyed' => true,
        ]);

      $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

}
