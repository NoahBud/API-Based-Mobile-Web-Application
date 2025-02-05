<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;


class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::factory()->count(20)->create();

        //nécessite d'avoir créer les Author avant
        foreach(Book::all() as $book) 
        {
            $authors = Author::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $book->authors()->attach($authors);
        }
    }
}
