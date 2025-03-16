<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            [
                'email' => 'admin@test.com',
            ],
            [
                'name' => 'Admin',
                'password' => 'admin',
            ]
        );

        $project = Project::firstOrCreate([
            'title' => 'Default project',
        ]);

        Task::factory(5)->create([
            'project_id' => $project->getKey(),
            'user_id' => $user->getKey(),
        ]);
    }
}
