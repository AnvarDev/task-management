<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\BaseApiTestCase;

class CommentsIndexTest extends BaseApiTestCase
{
    /**
     * Unauthenticated user trying to get comments list. 
     */
    public function test_comments_list_denied(): void
    {
        $this->json('GET', '/api/comment')->assertUnauthorized();
    }

    /**
     * Validation of the comments list request.
     */
    public function test_comments_list_validation(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->json('GET', '/api/comment', [
            'task_id' => 0,
            'limit' => 0,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'task_id',
            'limit'
        ]);
    }

    /**
     * Authorized user retrieves comments list by a task.
     */
    public function test_comments_list_can_be_retrieved(): void
    {
        $task = Task::factory()->create();
        Comment::factory()->count(5)->state([
            'task_id' => $task->getKey(),
        ])->create();

        Comment::factory()->count(2)->create();

        Sanctum::actingAs(User::factory()->create());

        $response = $this->json('GET', '/api/comment', [
            'task_id' => $task->getKey(),
        ]);

        $response->assertOk();
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'value',
                    'task_id',
                    'user_id',
                    'user' => [
                        'name',
                        'email'
                    ],
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
    }

    /**
     * Retrieve comments list for next page.
     */
    public function test_comments_list_pagination(): void
    {
        $task = Task::factory()->create();
        Comment::factory()->count(12)->state([
            'task_id' => $task->getKey(),
        ])->create();

        Sanctum::actingAs(User::factory()->create());

        $limit = 3;
        $page = 2;
        $response = $this->json('GET', '/api/comment', [
            'task_id' => $task->getKey(),
            'page' => $page,
            'limit' => $limit,
        ]);

        $response->assertOk();
        $response->assertJsonCount($limit, 'data');
        $this->assertTrue($response->json('meta.current_page') === $page);
    }
}
