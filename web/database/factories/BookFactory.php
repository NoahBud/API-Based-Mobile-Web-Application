<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Editor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $editorsID = Editor::get()->pluck('id');

        $genres = [
            'Fiction',
            'Science-Fiction',
            'Fantastique',
            'Romance',
            'Thriller',
            'Mystère',
            'Horreur',
            'Biographie',
            'Histoire',
            'Développement personnel'
        ];

        $descriptions = [
            "Un récit captivant d'aventure et de découverte de soi.",
            "Un mystère palpitant qui vous tient en haleine.",
            "Une histoire touchante d'amour et de perte.",
            "Une exploration perspicace des événements historiques.",
            "Un récit prenant qui plonge dans la psyché humaine.",
            "Un voyage fantastique à travers des royaumes magiques.",
            "Une analyse réfléchie des problèmes de société.",
            "Un drame policier plein de suspense avec des rebondissements inattendus.",
            "Une biographie fascinante d'une vie extraordinaire.",
            "Un guide pour la croissance personnelle et l'amélioration de soi."
        ];

        return [
            'ISBN' => $this->faker->unique()->isbn13,
            'name' => $this->faker->catchPhrase,
            'genre' => $this->faker->randomElement($genres),
            'description' => $this->faker->randomElement($descriptions),
            'editor_id' => $this->faker->randomElement($editorsID),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
