<?php

namespace Database\Factories;

use App\Models\Issue;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Issue>
 */
class IssueFactory extends Factory
{
    protected $model = Issue::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(3),
            'status' => fake()->randomElement(Issue::STATUSES),
            'priority' => fake()->randomElement(Issue::PRIORITIES),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+90 days'),
        ];
    }
}
