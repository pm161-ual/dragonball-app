<?php

namespace Database\Factories;

use App\Models\Planet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Planet>
 */
class PlanetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    /**
     * Define the model's default state.
     * Esta funcion sirve para generar datos falsos para la tabla planetas.
     */
    public function definition(): array
    {
        return [

            'name' => 'Planeta ' . fake()->unique()->word(),
            'is_destroyed' => fake()->boolean(20), // es decir que dentro del 100% hay un 20% de probabilidad de que sea true y un 80% de que sea false
            'description' => fake()->paragraph(),
            'image' => fake()->imageUrl(), 

        ];
    }
}
