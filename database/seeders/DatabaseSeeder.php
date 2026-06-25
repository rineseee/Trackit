<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()
            ->count(3)
            ->sequence(
                ['name' => 'Test User', 'email' => 'test@example.com'],
                ['name' => 'Ava Project Lead', 'email' => 'ava@example.com'],
                ['name' => 'Noah Reviewer', 'email' => 'noah@example.com'],
            )
            ->create();

        $tags = Tag::factory()
            ->count(6)
            ->sequence(
                ['name' => 'bug', 'color' => '#ef4444'],
                ['name' => 'frontend', 'color' => '#3b82f6'],
                ['name' => 'backend', 'color' => '#10b981'],
                ['name' => 'urgent', 'color' => '#f59e0b'],
                ['name' => 'ux', 'color' => '#8b5cf6'],
                ['name' => 'refactor', 'color' => '#64748b'],
            )
            ->create();

        Project::factory()
            ->count(3)
            ->state(fn (array $attributes): array => [
                'owner_id' => $users->random()->id,
            ])
            ->create()
            ->each(function (Project $project) use ($tags, $users): void {
                $issues = Issue::factory()
                    ->count(4)
                    ->for($project)
                    ->create();

                $issues->each(function (Issue $issue) use ($tags, $users): void {
                    $issue->tags()->attach(
                        $tags->random(rand(1, 3))->pluck('id')->all()
                    );

                    $issue->members()->attach(
                        $users->random(rand(1, 2))->pluck('id')->all()
                    );

                    Comment::factory()
                        ->count(2)
                        ->for($issue)
                        ->create();
                });
            });
    }
}
