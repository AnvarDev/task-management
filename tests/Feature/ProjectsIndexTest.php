<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\BaseApiTestCase;

class ProjectsIndexTest extends BaseApiTestCase
{
    /**
     * Unauthenticated user trying to get projects list. 
     */
    public function test_projects_list_denied(): void
    {
        $this->get('/api/project')->assertUnauthorized();
    }

    /**
     * Validation of the projects list request.
     */
    public function test_projects_list_validation(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->get('/api/project?limit=0');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'limit'
        ]);
    }

    /**
     * Authorized user retrieves projects list.
     */
    public function test_projects_list_can_be_retrieved(): void
    {
        Project::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $response = $this->get('/api/project');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'title',
                    'description',
                    'tasks',
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
     * Retrieve projects list for next page.
     */
    public function test_projects_list_pagination(): void
    {
        Project::factory()->count(10)->create();

        Sanctum::actingAs(User::factory()->create());

        $limit = 3;
        $page = 2;
        $response = $this->get('/api/project?page=' . $page . '&limit=' . $limit);

        $response->assertOk();
        $response->assertJsonCount($limit, 'data');
        $this->assertTrue($response->json('meta.current_page') === $page);
    }
}
