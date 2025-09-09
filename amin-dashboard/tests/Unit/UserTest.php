<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Freelancer;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_have_company_role()
    {
        $user = User::factory()->create(['role' => 'company']);

        $this->assertEquals('company', $user->role);
        $this->assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function user_can_have_freelancer_role()
    {
        $user = User::factory()->create(['role' => 'freelancer']);

        $this->assertEquals('freelancer', $user->role);
        $this->assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function user_can_have_admin_role()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->assertEquals('admin', $user->role);
        $this->assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function user_has_fillable_attributes()
    {
        $user = new User();

        $expectedFillable = [
            'username',
            'email',
            'password',
            'role',
            'email_verified_at'
        ];

        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    /** @test */
    public function user_can_be_verified()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->assertFalse($user->hasVerifiedEmail());

        $user->markEmailAsVerified();

        $this->assertTrue($user->hasVerifiedEmail());
    }
}
