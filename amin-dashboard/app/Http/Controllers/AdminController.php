<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\JobRequest;
use App\Models\Assignment;
use App\Models\Deliverable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function index(){

        $admin = Admin::all();
        return response()->json($admin);
    }

    public function store(Request $request){

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'admin_level' => 'string|in:standard,senior,super',
            'permissions' => 'nullable|array',
        ]);

        $admin = new Admin();
        $admin->user_id = $request->input('user_id');
        $admin->admin_level = $request->input('admin_level', 'standard');
        $admin->permissions = $request->input('permissions');
        $admin->save();

        return response()->json($admin, 201);
    }

    public function show($id){

        $admin = Admin::find($id);
        if ($admin){

            return response()->json($admin);
        } else {
            return response()->json(['message' => 'Admin not found'], 404);
        }
    }

    public function update(Request $request, $id){

        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'admin_level' => 'sometimes|string|in:standard,senior,super',
            'permissions' => 'nullable|array',
        ]);

        $admin = Admin::find($id);
        if ($request->has('user_id')) {
            $admin->user_id = $request->input('user_id');
        }
        if ($request->has('admin_level')) {
            $admin->admin_level = $request->input('admin_level');
        }
        if ($request->has('permissions')) {
            $admin->permissions = $request->input('permissions');
        }
        $admin->save();

        return response()->json($admin);
    }

    public function destroy($id){

        $admin = Admin::find($id);
        $admin->delete();
        return response()->json(null, 204);
    }

    /**
     * Get analytics data for admin dashboard
     */
    public function analytics()
    {
        // User statistics
        $userStats = [
            'total_users' => User::count(),
            'users_by_role' => User::select('role', DB::raw('count(*) as count'))
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
        ];

        // Job statistics
        $jobStats = [
            'total_jobs' => JobRequest::count(),
            'jobs_by_status' => JobRequest::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'jobs_this_month' => JobRequest::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'jobs_this_week' => JobRequest::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
        ];

        // Assignment statistics
        $assignmentStats = [
            'total_assignments' => Assignment::count(),
            'active_assignments' => Assignment::where('status', 'assigned')->count(),
            'completed_assignments' => Assignment::where('status', 'completed')->count(),
            'assignments_this_month' => Assignment::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Deliverable statistics
        $deliverableStats = [
            'total_deliverables' => Deliverable::count(),
            'pending_deliverables' => Deliverable::where('status', 'pending')->count(),
            'approved_deliverables' => Deliverable::where('status', 'approved')->count(),
            'rejected_deliverables' => Deliverable::where('status', 'rejected')->count(),
            'deliverables_this_month' => Deliverable::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Revenue statistics (if applicable)
        $revenueStats = [
            'total_jobs_value' => JobRequest::sum('budget') ?? 0,
            'completed_jobs_value' => JobRequest::where('status', 'completed')->sum('budget') ?? 0,
            'pending_jobs_value' => JobRequest::where('status', 'open')->sum('budget') ?? 0,
        ];

        return response()->json([
            'user_stats' => $userStats,
            'job_stats' => $jobStats,
            'assignment_stats' => $assignmentStats,
            'deliverable_stats' => $deliverableStats,
            'revenue_stats' => $revenueStats,
            'generated_at' => now()->toISOString(),
        ]);
    }

    /**
     * Get user growth chart data
     */
    public function userGrowth()
    {
        $userGrowth = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json($userGrowth);
    }

    /**
     * Get job status distribution
     */
    public function jobStatusDistribution()
    {
        $distribution = JobRequest::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json($distribution);
    }

    /**
     * Get monthly job creation statistics
     */
    public function monthlyJobStats()
    {
        $monthlyStats = JobRequest::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        return response()->json($monthlyStats);
    }

    /**
     * List users (admin only)
     */
    public function users()
    {
        return response()->json(User::select('id', 'username', 'email', 'role', 'email_verified_at')->get());
    }
}
