<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'company',
            'email_verified_at' => now()
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
            'device_name' => 'test_device'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'token',
                    'user' => [
                        'id',
                        'username',
                        'email',
                        'role'
                    ]
                ]);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
            'device_name' => 'test_device'
        ]);

        $response->assertStatus(401)
                ->assertJson(['message' => 'Invalid credentials']);
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
                ->assertJson(['message' => 'Logged out successfully']);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/v1/job-requests');

        $response->assertStatus(401);
    }

    /** @test */
    public function rate_limiting_works_for_login_attempts()
    {
        // Make multiple login attempts
        for ($i = 0; $i < 15; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'email' => 'test@example.com',
                'password' => 'password',
                'device_name' => 'test_device'
            ]);
        }

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
            'device_name' => 'test_device'
        ]);

        $response->assertStatus(429); // Too Many Requests
    }
}
