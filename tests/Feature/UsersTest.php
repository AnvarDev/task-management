<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\BaseApiTestCase;

class UsersTest extends BaseApiTestCase
{
    /**
     * Test user login endpoint required fields.
     */
    public function test_user_login_with_missed_fields(): void
    {
        $response = $this->json('POST', '/api/auth/login');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email',
            'password'
        ]);
    }

    /**
     * Retrieving a new user token with wrong credentials.
     */
    public function test_user_login_with_wrong_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => fake()->password(),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email'
        ]);
    }

    /**
     * Retrieving a new user token with right credentials.
     */
    public function test_user_login_with_right_credentials(): void
    {
        $password = fake()->password();
        $user = User::factory()->create([
            'password' => $password,
        ]);

        $response = $this->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'name',
                'email'
            ],
            'token'
        ]);
        $response->assertJsonFragment([
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Unauthenticated user trying to get user details.
     */
    public function test_user_details_denied(): void
    {
        $this->json('GET', '/api/auth/me')->assertUnauthorized();
    }

    /**
     * Authorized user retrieves user details.
     */
    public function test_user_details_can_be_retrieved(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->json('GET', '/api/auth/me');

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'name',
                'email'
            ],
        ]);
        $response->assertJsonFragment([
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Unauthenticated user trying to logout.
     */
    public function test_user_logout_fail(): void
    {
        $this->json('POST', '/api/auth/logout')->assertUnauthorized();
    }

    /**
     * Authorized user can be logged out.
     */
    public function test_user_logout_success(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->json('POST', '/api/auth/logout');

        $response->assertNoContent();
    }
}
