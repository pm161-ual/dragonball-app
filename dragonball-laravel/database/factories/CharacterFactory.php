<?php
namespace Database\Factories;

use App\Models\Character;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Planet;

class CharacterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Personaje ' . fake()->firstName(),
            'description' => fake()->paragraph(),
            'image' => fake()->imageUrl(),
            'race' => fake()->randomElement(['Saiyan', 'Namekian', 'Human', 'Android', 'Majin', 'Demon', 'God', 'Other']),
            'gender' => fake()->randomElement(['Male', 'Female', 'Unknown']),
            'affiliation' => fake()->randomElement(['Z-Fighter', 'Namekians', 'Androids', 'Majins', 'Demons', 'Gods', 'Other', 'Army of Frieza', 'Freelancer', 'Villain']),
            'ki' => (string)fake()->numberBetween(1000, 100000),
            'max_ki' => (string)fake()->numberBetween(200000, 900000),
            'planet_id' => Planet::factory(),

        ];
    }
}
