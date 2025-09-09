<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Freelancer;
use App\Models\JobRequest;
use App\Models\Assignment;
use App\Models\Deliverable;
use App\Models\StatusUpdate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $adminUser = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@freelance.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );
        // Mark as verified for testing/login convenience
        $adminUser->forceFill(['email_verified_at' => now()])->save();

        Admin::updateOrCreate(
            ['user_id' => $adminUser->id],
            [
                'admin_level' => 'super_admin',
                'permissions' => json_encode(['manage_users', 'manage_jobs', 'view_analytics'])
            ]
        );

        // Create Company User
        $companyUser = User::updateOrCreate(
            ['username' => 'company1'],
            [
                'email' => 'company@freelance.com',
                'password' => Hash::make('password'),
                'role' => 'company'
            ]
        );
        // Mark as verified for testing/login convenience
        $companyUser->forceFill(['email_verified_at' => now()])->save();

        Company::updateOrCreate(
            ['user_id' => $companyUser->id],
            [
                'company_name' => 'Tech Solutions Inc.',
                'contact_person' => 'John Smith',
                'phone' => '+1234567890',
                'address' => '123 Business St, City, State 12345',
                'industry' => 'Technology',
                'company_size' => '50-100 employees',
                'description' => 'Leading technology solutions provider specializing in web development and digital transformation.'
            ]
        );

        // Create Freelancer User
        $freelancerUser = User::updateOrCreate(
            ['username' => 'freelancer1'],
            [
                'email' => 'freelancer@freelance.com',
                'password' => Hash::make('password'),
                'role' => 'freelancer'
            ]
        );
        // Mark as verified for testing/login convenience
        $freelancerUser->forceFill(['email_verified_at' => now()])->save();

        Freelancer::updateOrCreate(
            ['user_id' => $freelancerUser->id],
            [
                // Additional profile fields can be added when corresponding columns exist
            ]
        );

        // Create Job Request
        $jobRequest = JobRequest::updateOrCreate(
            ['company_id' => 1, 'description' => 'Build a responsive e-commerce website with payment integration'],
            [
                'deadline' => now()->addDays(30),
                'status' => 'approved'
            ]
        );

        // Create Assignment
        $assignment = Assignment::updateOrCreate(
            ['job_request_id' => $jobRequest->id, 'freelancer_id' => 1],
            [
                'company_id' => 1
            ]
        );

        // Create Status Update
        StatusUpdate::updateOrCreate(
            ['assignment_id' => $assignment->id, 'status' => 'In Progress'],
            [
                'updated_on' => now(),
                'notes' => 'Started working on the project requirements'
            ]
        );

        // Create Deliverable
        Deliverable::updateOrCreate(
            ['assignment_id' => $assignment->id, 'content' => 'Initial project setup and wireframes completed'],
            [
                'submitted_on' => now(),
                'status' => 'pending'
            ]
        );
    }
}
