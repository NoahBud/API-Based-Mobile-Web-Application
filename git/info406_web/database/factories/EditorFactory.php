<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Editor>
 */
class EditorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $editors = [
            'Gallimard',
            'Flammarion',
            'Le Seuil',
            'Hachette Livre',
            'Fayard',
            'Albin Michel',
            'Grasset',
            'Actes Sud',
            'Robert Laffont',
            'Éditions de Minuit',
            'Penguin Random House',
            'HarperCollins',
            'Simon & Schuster',
            'Macmillan Publishers',
            'Scholastic',
            'Bloomsbury',
            'Oxford University Press',
            'Cambridge University Press',
            'Springer'
        ];

        return [
            'name' => fake()->unique()->randomElement($editors),
            'address' => fake()->address(),
            'mail' => fake()->email()
        ];

    }
}
