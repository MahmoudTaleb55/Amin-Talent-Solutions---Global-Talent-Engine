<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getUsers()
    {
        $users = User::with(['company', 'freelancer'])->get();
        return response()->json($users);
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,ceo,project_manager,company,freelancer',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        // Create profile based on role
        if ($request->role === 'company') {
            Company::create([
                'user_id' => $user->id,
                'name' => $request->company_name ?? $request->name,
            ]);
        } elseif ($request->role === 'freelancer') {
            Freelancer::create([
                'user_id' => $user->id,
            ]);
        }

        return response()->json($user->load(['company', 'freelancer']), 201);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'role' => 'sometimes|in:admin,ceo,project_manager,company,freelancer',
        ]);

        $user->update($request->only(['name', 'email']));

        if ($request->has('role')) {
            $user->syncRoles([$request->role]);
        }

        return response()->json($user->load(['company', 'freelancer']));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function getSystemStats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_companies' => Company::count(),
            'total_freelancers' => Freelancer::count(),
            'total_projects' => \App\Models\ProjectRequest::count(),
            'total_assignments' => \App\Models\Assignment::count(),
        ];

        return response()->json($stats);
    }
}
