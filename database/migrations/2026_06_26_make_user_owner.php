<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('email', 'rinesekrasniqii@gmail.com')
            ->update(['role' => 'owner']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', 'rinesekrasniqii@gmail.com')
            ->update(['role' => 'user']);
    }
};
