<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\BaseApiTestCase;

class TasksStatusTest extends BaseApiTestCase
{
    /**
     * Unauthenticated user trying to update the task status. 
     */
    public function test_update_task_status_denied(): void
    {
        $this->json('PUT', '/api/task/' . fake()->randomDigit() . '/status')->assertUnauthorized();
    }

    /**
     * Validation of the task update request.
     */
    public function test_update_task_status_validation(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->json('PUT', '/api/task/' . fake()->randomDigit() . '/status');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'status',
        ]);
    }

    /**
     * Authorized user but update request with an invalid ID.
     */
    public function test_update_task_status_invalid_id(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->json('PUT', '/api/task/' . fake()->randomDigit() . '/status', [
            'status' => fake()->randomKey(config('tasks.status'))
        ])->assertNotFound();
    }

    /**
     * Authorized user updates the task status.
     */
    public function test_update_task_status_can_be_processed(): void
    {
        $statuses = config('tasks.status');

        $status_1 = fake()->randomKey($statuses);

        $task = Task::factory()->state([
            'status' => $status_1,
        ])->create();

        unset($statuses[$status_1]);

        $status_2 = fake()->randomKey($statuses);

        Sanctum::actingAs(User::factory()->create());

        $response = $this->json('PUT', '/api/task/' . $task->getKey() . '/status', [
            'status' => $status_2,
        ]);

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

        $this->assertDatabaseHas(Task::class, [
            'id' => $task->getKey(),
            'status' => $status_2,
        ]);
    }
}
