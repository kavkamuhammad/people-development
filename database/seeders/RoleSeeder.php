<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'Admin',
            'display_name' => 'Administrator'
        ]);

        Role::create([
            'name' => 'Staff',
            'display_name' => 'Staff'
        ]);
    }
}
