<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Freelancer;
use App\Models\ProjectRequest;
use App\Models\Assignment;
use Illuminate\Http\Request;

class CEOController extends Controller
{
    public function getCompanyOverview()
    {
        $overview = [
            'total_users' => User::count(),
            'total_companies' => Company::count(),
            'total_freelancers' => Freelancer::count(),
            'active_projects' => ProjectRequest::where('status', 'active')->count(),
            'total_assignments' => Assignment::count(),
            'completed_assignments' => Assignment::where('status', 'completed')->count(),
        ];

        return response()->json($overview);
    }

    public function getFinancialReports()
    {
        // Placeholder for financial data - in real app, would aggregate from payments table
        $reports = [
            'total_revenue' => 0, // Sum of all payments
            'monthly_revenue' => [], // Monthly breakdown
            'outstanding_payments' => 0,
            'platform_fees' => 0,
        ];

        return response()->json($reports);
    }

    public function getUserManagement()
    {
        $users = User::with(['company', 'freelancer', 'roles'])->get();
        return response()->json($users);
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role' => 'required|in:admin,ceo,project_manager,company,employee,freelancer',
        ]);

        $user->syncRoles([$request->role]);

        return response()->json($user->load('roles'));
    }

    public function getSystemSettings()
    {
        // Placeholder for system settings
        $settings = [
            'platform_fee_percentage' => 10,
            'max_file_size_mb' => 10,
            'allowed_file_types' => ['pdf', 'doc', 'docx', 'jpg', 'png'],
        ];

        return response()->json($settings);
    }

    public function updateSystemSettings(Request $request)
    {
        // In real app, save to database
        $request->validate([
            'platform_fee_percentage' => 'numeric|min:0|max:100',
            'max_file_size_mb' => 'integer|min:1',
            'allowed_file_types' => 'array',
        ]);

        // Save settings logic here

        return response()->json(['message' => 'Settings updated']);
    }
}
