<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\BaseApiTestCase;

class TasksShowTest extends BaseApiTestCase
{
    /**
     * Unauthenticated user trying to get the task details. 
     */
    public function test_task_details_denied(): void
    {
        $this->get('/api/task/' . fake()->randomDigit())->assertUnauthorized();
    }

    /**
     * Authorized user but request with an invalid ID.
     */
    public function test_task_details_invalid_id(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->get('/api/task/' . fake()->randomDigit())->assertNotFound();
    }

    /**
     * Authorized user retrieves tasks list by a priority.
     */
    public function test_task_details_can_be_retrieved(): void
    {
        $task = Task::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $response = $this->get('/api/task/' . $task->getKey());

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'date',
                'priority',
                'status',
                'project_id',
                'user_id',
                'priority_name',
                'status_name',
                'project' => [
                    'id',
                    'title',
                    'description',
                    'tasks',
                ],
            ],
        ]);
    }
}
