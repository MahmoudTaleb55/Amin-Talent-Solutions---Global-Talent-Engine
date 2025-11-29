<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure roles exist
        $roles = ['admin','ceo','project_manager','company','freelancer'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // Create users for all roles
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        User::create([
            'name' => 'CEO User',
            'email' => 'ceo@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('ceo');

        User::create([
            'name' => 'Project Manager User',
            'email' => 'pm@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('project_manager');

        User::create([
            'name' => 'Company User',
            'email' => 'company@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('company');

        User::create([
            'name' => 'Freelancer User',
            'email' => 'freelancer@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('freelancer');

    }
}
