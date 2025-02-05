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

        return [
            'ISBN' => $this->faker->randomNumber(9), 
            'name' => $this->faker->name(), 
            'genre' => $this->faker->realText(30),
            'description' => $this->faker->realText(200),
            'editor_id' => fake()->randomElement($editorsID),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
