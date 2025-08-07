<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create(['password' => '0000']);
        foreach (User::all() as $user){
            $role = fake()->randomElement(['admin', 'employe']);
            $user->assignRole($role);
        }

        $user = User::factory()->create([ 
            'name' => 'Noah',
            'email' => 'test@admin.fr',
            'password' => '0000',
        ]);
        $user->assignRole('admin');

        $user = User::factory()->create([ 
            'name' => 'Rayan',
            'email' => 'test@employe.fr',
            'password' => '0000',
        ]);
        $user->assignRole('employe');

        $user = User::factory()->create([ 
            'name' => 'Lawrence',
            'email' => 'test@visiteur.fr',
            'password' => '0000',
        ]);
        $user->assignRole('visiteur');
    }
}
