<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::create(['name' => 'manage users']));

        $role = Role::create(['name' => 'employe']); // => gens qui font l'inventaire 
        $role->givePermissionTo(Permission::create(['name' => 'manage books']));
        
    }
}
