<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'email_verified_at' => null,
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'role' => 'admin',
        ]);
        User::create([
                'name' => 'Contractor Company',
                'email' => 'contractorcompany@email.com',
                'email_verified_at' => null,
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'role' => 'user',
        ]);
        User::create([
                'name' => 'Logistics Company',
                'email' => 'logisticscompany@email.com',
                'email_verified_at' => null,
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'role' => 'user',
        ]);
        // Add more default users as needed with their respective roles
    }
}

