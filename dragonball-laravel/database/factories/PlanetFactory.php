<?php

namespace Database\Factories;

use App\Models\Planet;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanetFactory extends Factory
{

    public function definition(): array
    {
        return [

            'name' => 'Planeta ' . fake()->unique()->word(),
            'is_destroyed' => fake()->boolean(20),
            'description' => fake()->paragraph(),
            'image' => fake()->imageUrl(),

        ];
    }
}
