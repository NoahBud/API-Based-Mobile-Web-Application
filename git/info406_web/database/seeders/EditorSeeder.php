<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Editor;


class EditorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Editor::firstOrCreate([
        //     'name' => 'Aucun renseigné',
        //     'address' => 'Adresse inconnue',
        //     'mail' => 'non-renseigné@example.com'
        // ]);
        Editor::factory()->count(10)->create();
    }
}
