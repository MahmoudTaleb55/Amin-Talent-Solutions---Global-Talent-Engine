<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for users
        $permissions = [
            // User management
            'view-users', 'create-user', 'edit-user', 'delete-user',
            // Project management
            'view-projects', 'create-project', 'edit-project', 'delete-project',
            // Assignment management
            'view-assignments', 'create-assignment', 'edit-assignment', 'delete-assignment',
            // Freelancer management
            'view-freelancers', 'create-freelancer', 'edit-freelancer', 'delete-freelancer',
            // Job management
            'view-jobs', 'post-job', 'edit-job', 'delete-job',
            // Deliverable management
            'submit-deliverable', 'review-deliverable',
            // Financial reports
            'view-financial-reports', 'export-reports',
            // System settings
            'view-system-settings', 'edit-system-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $ceoRole = Role::firstOrCreate(['name' => 'ceo']);
        $pmRole = Role::firstOrCreate(['name' => 'project_manager']);
        $companyRole = Role::firstOrCreate(['name' => 'company']);
        $freelancerRole = Role::firstOrCreate(['name' => 'freelancer']);

        // Assign permissions to Admin role
        $adminRole->syncPermissions([
            'view-users', 'create-user', 'edit-user', 'delete-user',
            'view-projects', 'view-assignments', 'view-system-settings', 'edit-system-settings',
        ]);

        // Assign permissions to CEO role
        $ceoRole->syncPermissions([
            'view-users', 'view-projects', 'view-financial-reports', 'export-reports',
            'view-system-settings',
        ]);

        // Assign permissions to Project Manager role
        $pmRole->syncPermissions([
            'view-projects', 'create-project', 'edit-project', 'delete-project',
            'view-assignments', 'create-assignment', 'edit-assignment', 'delete-assignment',
            'view-freelancers',
        ]);

        // Assign permissions to Company role
        $companyRole->syncPermissions([
            'view-jobs', 'post-job', 'edit-job', 'delete-job',
            'view-freelancers', 'view-assignments',
        ]);

        // Assign permissions to Freelancer role
        $freelancerRole->syncPermissions([
            'view-assignments', 'submit-deliverable', 'review-deliverable',
        ]);
    }
}