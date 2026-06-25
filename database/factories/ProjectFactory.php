<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-3 months', 'now');
        $deadline = fake()->dateTimeBetween($startDate, '+6 months');

        return [
            'owner_id' => null,
            'name' => fake()->unique()->company().' Project',
            'description' => fake()->paragraph(),
            'start_date' => $startDate,
            'deadline' => $deadline,
        ];
    }
}
