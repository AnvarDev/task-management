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
        $project = Project::firstOrCreate([
            'title' => 'Default project',
        ]);

        $values = [
            [
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'password' => 'admin',
            ],
            [
                'name' => 'User',
                'email' => 'user@test.com',
                'password' => 'user',
            ],
        ];

        foreach ($values as $value) {
            $user = User::firstOrCreate(
                [
                    'email' => $value['email'],
                ],
                [
                    'name' => $value['name'],
                    'password' => $value['password'],
                ]
            );

            Task::factory(5)->create([
                'project_id' => $project->getKey(),
                'user_id' => $user->getKey(),
            ]);
        }
    }
}
