<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test creating a user
$user = \App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('TestPassword123!'),
    'email_verified_at' => now(),
    'is_active' => true,
    'role' => 'user',
    'created_ip' => '127.0.0.1'
]);

echo "User created successfully with ID: {$user->id}\n";
echo "Email: {$user->email}\n";
echo "Email verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
