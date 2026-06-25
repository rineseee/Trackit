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
        // This migration must be safe on a fresh database where the tables may not
        // exist yet. Only add indexes to tables that are already present.
        if (Schema::hasTable('issues')) {
            Schema::table('issues', function (Blueprint $table) {
                if (!Schema::hasIndex('issues', ['status'])) {
                    $table->index('status');
                }
                if (!Schema::hasIndex('issues', ['priority'])) {
                    $table->index('priority');
                }
                if (!Schema::hasIndex('issues', ['created_at'])) {
                    $table->index('created_at');
                }
                if (!Schema::hasIndex('issues', ['project_id'])) {
                    $table->index('project_id');
                }
            });
        }

        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                if (!Schema::hasIndex('comments', ['issue_id'])) {
                    $table->index('issue_id');
                }
                if (!Schema::hasIndex('comments', ['created_at'])) {
                    $table->index('created_at');
                }
            });
        }

        if (Schema::hasTable('projects')) {
            Schema::table('projects', function (Blueprint $table) {
                if (!Schema::hasIndex('projects', ['created_at'])) {
                    $table->index('created_at');
                }
                if (!Schema::hasIndex('projects', ['owner_id'])) {
                    $table->index('owner_id');
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasIndex('users', ['created_at'])) {
                    $table->index('created_at');
                }
            });
        }

        if (Schema::hasTable('tags')) {
            Schema::table('tags', function (Blueprint $table) {
                if (!Schema::hasIndex('tags', ['created_at'])) {
                    $table->index('created_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['project_id']);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['issue_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['owner_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
};
