<?php

namespace Tests\Feature;

use App\Events\TaskHasBeenUpdated;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\BaseApiTestCase;

class TasksPriorityTest extends BaseApiTestCase
{
    /**
     * Unauthenticated user trying to update the task priority. 
     */
    public function test_update_task_priority_denied(): void
    {
        $this->json('PUT', '/api/task/' . fake()->randomDigit() . '/priority')->assertUnauthorized();
    }

    /**
     * Validation of the task update request.
     */
    public function test_update_task_priority_validation(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->json('PUT', '/api/task/' . fake()->randomDigit() . '/priority');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'priority',
        ]);
    }

    /**
     * Authorized user but update request with an invalid ID.
     */
    public function test_update_task_priority_invalid_id(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->json('PUT', '/api/task/' . fake()->randomDigit() . '/priority', [
            'priority' => fake()->randomKey(config('tasks.priority'))
        ])->assertNotFound();
    }

    /**
     * Authorized user updates the task priority.
     */
    public function test_update_task_priority_can_be_processed(): void
    {
        Event::fake();

        $priorities = config('tasks.priority');

        $priority_1 = fake()->randomKey($priorities);

        $task = Task::factory()->state([
            'priority' => $priority_1,
        ])->create();

        unset($priorities[$priority_1]);

        $priority_2 = fake()->randomKey($priorities);

        Sanctum::actingAs(User::factory()->create());

        $response = $this->json('PUT', '/api/task/' . $task->getKey() . '/priority', [
            'priority' => $priority_2,
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
            'priority' => $priority_2,
        ]);

        Event::assertDispatched(TaskHasBeenUpdated::class);
    }
}
