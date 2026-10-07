<?php
/**
 * El facotry inventa personajes falsos pero realistas antes de que pruebe la API 
 */
namespace Database\Factories;

use App\Models\Character;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Planet; // Importo Planet porque lo usare para asignar planeta a los personajes

/**
 * @extends Factory<Character>
 */
class CharacterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Personaje ' . fake()->firstName(), // Genera un nombre de personaje aleatorio falso
            'description' => fake()->paragraph(), // Genera una descripción aleatoria falsa
            'image' => fake()->imageUrl(),  // Genera una URL de imagen aleatoria falsa
            'race' => fake()->randomElement(['Saiyan', 'Namekian', 'Human', 'Android', 'Majin', 'Demon', 'God', 'Other']), // Esto genera una raza aleatoria falsa
            'gender' => fake()->randomElement(['Male', 'Female', 'Unknown']), // Genera un género aleatorio falsa. El randomElement elige un valor aleatorio de un array
            'affiliation' => fake()->randomElement(['Z-Fighter', 'Namekians', 'Androids', 'Majins', 'Demons', 'Gods', 'Other', 'Army of Frieza', 'Freelancer', 'Villain']), // Genera una afiliación aleatoria falsa
            'ki' => (string)fake()->numberBetween(1000, 100000), // Genera un ki aleatorio falso entre 1000 y 100000 al azar 
            'max_ki' => (string)fake()->numberBetween(200000, 900000), // Genera un ki maximo aleatorio falso entre 20000 y 900000 al azar
            'planet_id' => Planet::factory(), // El planet_id crea un planeta nuevo y se lo asigna a un personaje

        ];
    }
}
