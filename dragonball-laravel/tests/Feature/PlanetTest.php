<?php

namespace Tests\Feature;

use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class PlanetTest extends TestCase
{
    use RefreshDatabase; // Esto asegura que la base de datos se reinicie después de cada prueba, para que no haya interferencias entre pruebas.

    public function test_listar_planetas(): void 
    {
        // 1. Preparar en este caso se crea 3 planetas en la base de datos usando el factory de Planet
        Planet::factory()->count(3)->create();

        // 2. Hace que la peticion Get use la URL /api/planets y guarda la respuesta en la variable $response
        $response = $this->getJson('/api/planets');

        // 3. Comprueba que la respuesta tiene un código de estado 200 (OK) y que el JSON devuelto tiene 3 elementos en el array 'data'
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }
    public function test_crear_planeta_sin_token(): void
    {
        $response = $this->postJson('/api/planets' , [
            'name' => 'Vegeta',
            'is_destroyed' => true,

        ]);

        $response->assertStatus(401); // Comprueba que la respuesta tiene un código de estado 401 (No autorizado)
    }

    public function test_crear_planeta_con_token(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/planets', [
            'name' => 'Vegeta',
            'is_destroyed' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('planets', ['name' => 'Vegeta']);
    }
}
