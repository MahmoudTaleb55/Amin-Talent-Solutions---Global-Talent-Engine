<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Invoice;

class E2EFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_e2e_invoice_payment_and_release()
    {
        // Ensure roles
        $roles = ['admin','ceo','project_manager','company','freelancer'];
        foreach ($roles as $r) Role::firstOrCreate(['name' => $r]);

        // Create admin and freelancer
        $admin = User::create(['name' => 'Admin Tester','email' => 'admintest@example.com','password' => bcrypt('password')]);
        $admin->assignRole('admin');

        $freelancer = User::create(['name' => 'Freelancer Tester','email' => 'freelancertest@example.com','password' => bcrypt('password')]);
        $freelancer->assignRole('freelancer');

        // create a minimal project record (tests expect project id 1)
        \DB::table('projects')->insert([
            'id' => 1,
            'title' => 'Test Project',
            'description' => 'Auto-created project for tests',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Acting as freelancer create invoice
        $this->actingAs($freelancer, 'sanctum');
        $resp = $this->postJson('/api/projects/1/invoice', [
            'amount' => 50,
            'currency' => 'USD',
            'description' => 'Test invoice',
            'company_id' => $admin->id
        ]);
        $resp->assertStatus(201);
        $invoiceId = $resp->json('id');
        $this->assertNotNull($invoiceId);

        // Acting as admin simulate payment
        $this->actingAs($admin, 'sanctum');
        $sim = $this->postJson("/api/payments/test/invoice/{$invoiceId}/simulate");
        $sim->assertStatus(200);

        $invoice = Invoice::find($invoiceId);
        $this->assertEquals('paid', $invoice->status);

        // Release funds as admin
        $rel = $this->postJson("/api/invoices/{$invoiceId}/release");
        $rel->assertStatus(200);

        $invoice->refresh();
        $this->assertEquals('released', $invoice->status);

        // Audit log exists
        $this->assertDatabaseHas('audit_logs', ['action' => 'invoice_released', 'meta->invoice_id' => $invoiceId]);
    }
}
