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
        if (!Schema::hasColumn('issues', 'issue_number')) {
            Schema::table('issues', function (Blueprint $table) {
                $table->unsignedInteger('issue_number')->default(1);
                $table->unique(['project_id', 'issue_number']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('issues', 'issue_number')) {
            Schema::table('issues', function (Blueprint $table) {
                $table->dropUnique(['project_id', 'issue_number']);
                $table->dropColumn('issue_number');
            });
        }
    }
};
