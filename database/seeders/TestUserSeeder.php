<?php

namespace Database\Seeders;

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
        // Create test users for authentication testing
        $users = [
            [
                'name' => 'Test User 1',
                'email' => 'testuser1@example.com',
                'password' => Hash::make('TestPassword123!'),
                'email_verified_at' => now(),
                'is_active' => true,
                'role' => 'user',
                'created_ip' => '127.0.0.1'
            ],
            [
                'name' => 'Test User 2',
                'email' => 'testuser2@example.com',
                'password' => Hash::make('AnotherPass456@'),
                'email_verified_at' => now(),
                'is_active' => true,
                'role' => 'user',
                'created_ip' => '127.0.0.1'
            ]
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        echo "Test users created successfully!\n";
        echo "User 1: testuser1@example.com / TestPassword123!\n";
        echo "User 2: testuser2@example.com / AnotherPass456@\n";
    }
}

