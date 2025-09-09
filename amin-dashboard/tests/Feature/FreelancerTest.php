<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Freelancer;
use App\Models\Assignment;
use App\Models\Deliverable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class FreelancerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function freelancer_can_view_assigned_work()
    {
        $user = User::factory()->create(['role' => 'freelancer']);
        $freelancer = Freelancer::factory()->create(['user_id' => $user->id]);
        Assignment::factory()->create(['freelancer_id' => $freelancer->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/freelancer/assignments');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'job_request_id',
                            'freelancer_id',
                            'company_id'
                        ]
                    ]
                ]);
    }

    /** @test */
    public function freelancer_can_update_assignment_status()
    {
        $user = User::factory()->create(['role' => 'freelancer']);
        $freelancer = Freelancer::factory()->create(['user_id' => $user->id]);
        $assignment = Assignment::factory()->create(['freelancer_id' => $freelancer->id]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/v1/freelancer/assignments/{$assignment->id}/status", [
            'status' => 'in_progress'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'assignment_id',
                    'status',
                    'updated_on'
                ]);

        $this->assertDatabaseHas('status_updates', [
            'assignment_id' => $assignment->id,
            'status' => 'in_progress'
        ]);
    }

    /** @test */
    public function freelancer_can_submit_deliverable()
    {
        $user = User::factory()->create(['role' => 'freelancer']);
        $freelancer = Freelancer::factory()->create(['user_id' => $user->id]);
        $assignment = Assignment::factory()->create(['freelancer_id' => $freelancer->id]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/v1/freelancer/assignments/{$assignment->id}/deliver", [
            'content' => 'Completed work deliverable'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'assignment_id',
                    'content',
                    'submitted_on',
                    'status'
                ]);

        $this->assertDatabaseHas('deliverables', [
            'assignment_id' => $assignment->id,
            'content' => 'Completed work deliverable',
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function freelancer_cannot_access_other_freelancers_assignments()
    {
        $user1 = User::factory()->create(['role' => 'freelancer']);
        $user2 = User::factory()->create(['role' => 'freelancer']);
        $freelancer1 = Freelancer::factory()->create(['user_id' => $user1->id]);
        $freelancer2 = Freelancer::factory()->create(['user_id' => $user2->id]);

        Assignment::factory()->create(['freelancer_id' => $freelancer2->id]);
        Sanctum::actingAs($user1);

        $response = $this->getJson('/api/v1/freelancer/assignments');

        $response->assertStatus(200)
                ->assertJsonCount(0, 'data');
    }
}
