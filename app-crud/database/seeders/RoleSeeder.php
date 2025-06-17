<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'roleName' => 'user',
            'roleDescription' => 'Default role for newly registered users.',
        ]);
        Role::create([
            'roleName' => 'admin',
            'roleDescription' => 'System administrator with full access.',
        ]);
    }
}
