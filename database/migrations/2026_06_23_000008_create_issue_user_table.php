<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_user', function (Blueprint $table) {
            $table->foreignId('issue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['issue_id', 'user_id']);
        });

        $userIds = DB::table('users')->orderBy('id')->limit(2)->pluck('id');

        if ($userIds->isNotEmpty()) {
            $rows = DB::table('issues')
                ->orderBy('id')
                ->pluck('id')
                ->flatMap(function (int $issueId) use ($userIds): array {
                    return $userIds->map(fn (int $userId): array => [
                        'issue_id' => $issueId,
                        'user_id' => $userId,
                    ])->all();
                })
                ->all();

            if ($rows) {
                DB::table('issue_user')->insert($rows);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_user');
    }
};
