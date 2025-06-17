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
            'id' => Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'email_verified_at' => null,
            'password' => Hash::make('1234'),
            'remember_token' => Str::random(10),
            'role' => 'admin',
            'verificationStatus' => 'Approved',
            'accountStatus' => 'Active',
        ]);
        User::create([
                'id' => Str::uuid(),
                'name' => 'Contractor Company',
                'email' => 'contractorcompany@email.com',
                'email_verified_at' => null,
                'password' => Hash::make('1234'),
                'remember_token' => Str::random(10),
                'role' => 'user',
                'verificationStatus' => 'Approved',
                'accountStatus' => 'Active',
        ]);
        User::create([
                'id' => Str::uuid(),
                'name' => 'Logistics Company',
                'email' => 'logisticscompany@email.com',
                'email_verified_at' => null,
                'password' => Hash::make('1234'),
                'remember_token' => Str::random(10),
                'role' => 'user',
                'verificationStatus' => 'Approved',
                'accountStatus' => 'Active',
        ]);
        // Add more default users as needed with their respective roles
    }
}

