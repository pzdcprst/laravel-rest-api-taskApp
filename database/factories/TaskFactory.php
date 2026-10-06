<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(Status::values()),
            'priority' => fake()->randomElement(Priority::values()),
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'due_date' => fake()->dateTimeBetween('-7 days', '+30 days')->format('Y-m-d'),
        ];
    }

    public function forProject(Project $project): static
    {
        return $this->state(fn (array $attributes): array => [
            'project_id' => $project->id,
            'user_id' => $project->user_id,
        ]);
    }
}
