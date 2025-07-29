<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Box>
 */
class BoxFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $etats = [
            'Neuf',
            'Bon',
            'Usé',
            'Très usé',
            'A remplacer'
        ];

        $coordinates = [
            [
                'address' => '10 Rue de la Paix, 75002 Paris',
                'longitude' => 2.3303, 
                'latitude' => 48.8686
            ], // Paris
            [
                'address' => '2 Place Bellecour, 69002 Lyon',
                'longitude' => 4.8320, 
                'latitude' => 45.7579
            ], // Lyon
            [
                'address' => '1 Quai de la Fraternité, 13001 Marseille',
                'longitude' => 5.3734, 
                'latitude' => 43.2951
            ], // Marseille
            [
                'address' => '5 Cours du Chapeau-Rouge, 33000 Bordeaux',
                'longitude' => -0.5726, 
                'latitude' => 44.8410
            ], // Bordeaux
            [
                'address' => '3 Place du Capitole, 31000 Toulouse',
                'longitude' => 1.4442, 
                'latitude' => 43.6044
            ], // Toulouse
        ];

        // Sélection aléatoire parmi les adresses et leurs coordonnées
        $randomCoordinates = $this->faker->randomElement($coordinates);

        $boxNames = [
            'Livres en Partage',
            'Petite Lecture Pépouze',
            'Bibliothèque de Rue',
            'Livres en Liberté',
            'Coin des Lecteurs',
            'Échange de Pages',
            'La transmission culturel',
            'Bibliothèque Partagée',
            'Lecture en groupe',
            'Pages affolées'
        ];

        return [
            'name' => $this->faker->randomElement($boxNames), 
            'etat' => fake()->randomElement($etats),
            'longitude' => $randomCoordinates['longitude'], 
            'latitude' => $randomCoordinates['latitude'],  
            'address' => $randomCoordinates['address']
        ];
    }
}
