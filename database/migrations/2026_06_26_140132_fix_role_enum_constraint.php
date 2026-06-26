<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to handle enum constraints differently
        // Since SQLite doesn't support ALTER COLUMN for enums, we'll recreate the table structure

        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't have a simple way to alter enum constraints
            // We'll need to create a new table without the CHECK constraint and copy data
            DB::statement('PRAGMA foreign_keys = OFF');

            // Create a new users table without the strict role enum
            DB::statement('
                CREATE TABLE users_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name VARCHAR NOT NULL,
                    email VARCHAR NOT NULL UNIQUE,
                    email_verified_at DATETIME,
                    password VARCHAR NOT NULL,
                    is_active BOOLEAN DEFAULT 1,
                    role VARCHAR DEFAULT "user",
                    failed_login_attempts INTEGER DEFAULT 0,
                    last_login_at DATETIME,
                    last_login_ip VARCHAR,
                    created_ip VARCHAR,
                    phone VARCHAR,
                    company VARCHAR,
                    bio TEXT,
                    preferences JSON,
                    remember_token VARCHAR,
                    created_at DATETIME,
                    updated_at DATETIME
                )
            ');

            // Copy data from old table
            DB::statement('
                INSERT INTO users_new
                SELECT id, name, email, email_verified_at, password, is_active, role,
                       failed_login_attempts, last_login_at, last_login_ip, created_ip,
                       phone, company, bio, preferences, remember_token, created_at, updated_at
                FROM users
            ');

            // Drop old table and rename new one
            DB::statement('DROP TABLE users');
            DB::statement('ALTER TABLE users_new RENAME TO users');

            // Set Rinesa as owner
            DB::statement("UPDATE users SET role = 'owner' WHERE id = 4");

            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            // For other databases, just update the enum
            DB::statement("UPDATE users SET role = 'owner' WHERE id = 4");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback would require recreating the original table structure
        // For now, we'll just skip the rollback
    }
};
