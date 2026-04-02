<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Create the Role (Specify guard_name to be safe)
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // 2. Create the Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('1123'), 
                'email_verified_at' => now(),
            ]
        );

        // 3. Assign Role
        $admin->assignRole($adminRole);

        // 4. Create a regular Test User (No admin access)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );
    }
}