<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\BaseApiTestCase;

class TasksIndexTest extends BaseApiTestCase
{
    /**
     * Unauthenticated user trying to get tasks list. 
     */
    public function test_tasks_list_denied(): void
    {
        $this->get('/api/task')->assertUnauthorized();
    }

    /**
     * Validation of the tasks list request.
     */
    public function test_tasks_list_validation(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->get('/api/task?priority=-1&project_id=0&limit=0');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'priority',
            'project_id',
            'limit',
        ]);
    }

    /**
     * Authorized user retrieves tasks list by a priority.
     */
    public function test_tasks_list_by_priority_can_be_retrieved(): void
    {
        $priorities = config('tasks.priority');

        $priority_1 = fake()->randomKey($priorities);

        Task::factory()->count(7)->state([
            'priority' => $priority_1,
        ])->create();

        unset($priorities[$priority_1]);

        $priority_2 = fake()->randomKey($priorities);

        Task::factory()->count(3)->state([
            'priority' => $priority_2,
        ])->create();

        Sanctum::actingAs(User::factory()->create());

        $response = $this->get('/api/task?priority=' . $priority_1);

        $response->assertOk();
        $response->assertJsonCount(7, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'date',
                    'priority',
                    'title',
                    'status',
                    'priority_name',
                    'status_name',
                ]
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'per_page',
                'to',
                'total',
            ],
        ]);

        $response = $this->get('/api/task?priority=' . $priority_2);

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /**
     * Authorized user retrieves tasks list by a priority and a project.
     */
    public function test_tasks_list_by_priority_and_project_can_be_retrieved(): void
    {
        $project_1 = Project::factory()->create();
        $project_2 = Project::factory()->create();

        $priority = fake()->randomKey(config('tasks.priority'));

        Task::factory()->count(7)->state([
            'priority' => $priority,
            'project_id' => $project_1,
        ])->create();

        Task::factory()->count(3)->state([
            'priority' => $priority,
            'project_id' => $project_2,
        ])->create();

        Sanctum::actingAs(User::factory()->create());

        $response = $this->get('/api/task?priority=' . $priority);

        $response->assertOk();
        $response->assertJsonCount(10, 'data');

        $response = $this->get('/api/task?priority=' . $priority . '&project_id=' . $project_1->getKey());

        $response->assertOk();
        $response->assertJsonCount(7, 'data');

        $response = $this->get('/api/task?priority=' . $priority . '&project_id=' . $project_2->getKey());

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /**
     * Retrieve tasks list for next page.
     */
    public function test_tasks_list_pagination(): void
    {
        $priority = fake()->randomKey(config('tasks.priority'));

        Task::factory()->count(7)->state([
            'priority' => $priority,
        ])->create();

        Sanctum::actingAs(User::factory()->create());

        $limit = 3;
        $page = 2;
        $response = $this->get('/api/task?priority=' . $priority . '&page=' . $page . '&limit=' . $limit);

        $response->assertOk();
        $response->assertJsonCount($limit, 'data');
        $this->assertTrue($response->json('meta.current_page') === $page);
    }
}
