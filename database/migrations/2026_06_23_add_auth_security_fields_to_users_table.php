<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add security fields if they don't exist
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['user', 'admin', 'manager'])->default('user')->after('is_active');
            }
            if (!Schema::hasColumn('users', 'failed_login_attempts')) {
                $table->integer('failed_login_attempts')->default(0)->after('role');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('failed_login_attempts');
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->ipAddress('last_login_ip')->nullable()->after('last_login_at');
            }
            if (!Schema::hasColumn('users', 'created_ip')) {
                $table->string('created_ip')->nullable()->after('last_login_ip');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'role',
                'failed_login_attempts',
                'last_login_at',
                'last_login_ip',
                'created_ip',
            ]);
        });
    }
};
