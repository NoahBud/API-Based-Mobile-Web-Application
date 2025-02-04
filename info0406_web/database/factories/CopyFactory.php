<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Copy>
 */
class CopyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    static $counter = 1; 

    public function definition(): array
    {
        $booksID = Book::get()->pluck('id');

        return [
            'numero_exemplaire' => self::$counter++, 
            'etat' => $this->faker->randomElement(['neuf', 'bon', 'usé']),
            'disponibilite' => $this->faker->boolean(),
            'book_id' => fake()->randomElement($booksID), //on récupère un id aléatoire parmi les livres
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
