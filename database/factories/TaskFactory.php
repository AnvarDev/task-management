<?php

namespace Database\Factories;

use App\Models\Priority;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->text(),
            'date' => now()->addMonth(1),
            'priority' => fake()->randomKey(config('tasks.priority')),
            'status' => fake()->randomKey(config('tasks.status')),
            'project_id' => Project::factory(),
        ];
    }
}
