<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\BaseApiTestCase;

class CommentsStoreTest extends BaseApiTestCase
{
    /**
     * Unauthenticated user trying to store a comment. 
     */
    public function test_comments_store_denied(): void
    {
        $this->json('POST', '/api/comment')->assertUnauthorized();
    }

    /**
     * Validation of the comments store request.
     */
    public function test_comments_store_validation(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->json('POST', '/api/comment', [
            'task_id' => 0,
            'text' => fake()->randomLetter(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'task_id',
            'text'
        ]);
    }

    /**
     * Authorized user creates a new comment by a task.
     */
    public function test_comments_store_can_be_processed(): void
    {
        $task = Task::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $response = $this->json('POST', '/api/comment', [
            'task_id' => $task->getKey(),
            'text' => fake()->sentence(3),
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'value',
                'task_id',
                'user_id',
                'user' => [
                    'name',
                    'email'
                ],
            ],
        ]);

        $this->assertDatabaseHas(Comment::class, [
            'id' => $response->json('data.id'),
            'task_id' => $task->getKey(),
        ]);
    }
}
