<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\JobRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function company_can_create_job_request()
    {
        $user = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/job-requests', [
            'description' => 'Build a website',
            'deadline' => '2024-12-31'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'company_id',
                    'description',
                    'deadline',
                    'status'
                ]);

        $this->assertDatabaseHas('job_requests', [
            'company_id' => $company->id,
            'description' => 'Build a website',
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function company_can_view_their_job_requests()
    {
        $user = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create(['user_id' => $user->id]);
        JobRequest::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/job-requests');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'company_id',
                            'description',
                            'deadline',
                            'status'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function company_cannot_view_other_companies_job_requests()
    {
        $user1 = User::factory()->create(['role' => 'company']);
        $user2 = User::factory()->create(['role' => 'company']);
        $company1 = Company::factory()->create(['user_id' => $user1->id]);
        $company2 = Company::factory()->create(['user_id' => $user2->id]);

        JobRequest::factory()->create(['company_id' => $company2->id]);
        Sanctum::actingAs($user1);

        $response = $this->getJson('/api/v1/job-requests');

        $response->assertStatus(200)
                ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function company_can_hire_freelancer()
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        $freelancerUser = User::factory()->create(['role' => 'freelancer']);
        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $freelancer = \App\Models\Freelancer::factory()->create(['user_id' => $freelancerUser->id]);
        $jobRequest = JobRequest::factory()->create(['company_id' => $company->id]);

        Sanctum::actingAs($companyUser);

        $response = $this->postJson("/api/v1/job-requests/{$jobRequest->id}/hire", [
            'freelancer_id' => $freelancer->id
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'job_request_id',
                    'freelancer_id',
                    'company_id'
                ]);
    }
}
