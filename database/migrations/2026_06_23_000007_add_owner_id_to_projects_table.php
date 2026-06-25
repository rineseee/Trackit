<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('owner_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });

        $firstUserId = DB::table('users')->orderBy('id')->value('id');

        if ($firstUserId) {
            DB::table('projects')
                ->whereNull('owner_id')
                ->update(['owner_id' => $firstUserId]);
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('owner_id');
        });
    }
};
