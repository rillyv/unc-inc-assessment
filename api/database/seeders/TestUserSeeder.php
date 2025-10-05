<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
            ]
        );
        User::firstOrCreate(
            ['email' => 'author@example.com'],
            [
                'name' => 'Author User',
                'password' => Hash::make('password'),
                'role' => UserRole::AUTHOR,
            ]
        );
        User::firstOrCreate(
            ['email' => 'author2@example.com'],
            [
                'name' => 'Author 2 User',
                'password' => Hash::make('password'),
                'role' => UserRole::AUTHOR,
            ]
        );
         User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'password' => Hash::make('password'),
            ]
        );
    }
}
