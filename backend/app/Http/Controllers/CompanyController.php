<?php

namespace App\Http\Controllers;

use App\Models\ProjectRequest;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function postJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'budget' => 'required|numeric|min:0',
            'deadline' => 'required|date',
        ]);

        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['message' => 'Company profile not found'], 404);
        }

        $job = ProjectRequest::create([
            'company_id' => $company->id,
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'budget' => $request->budget,
            'deadline' => $request->deadline,
            'status' => 'open',
        ]);

        return response()->json($job, 201);
    }

    public function getMyJobs()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['message' => 'Company profile not found'], 404);
        }

        $jobs = ProjectRequest::where('company_id', $company->id)
            ->with(['assignments.freelancer.user'])
            ->get();

        return response()->json($jobs);
    }

    public function getApplications()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['message' => 'Company profile not found'], 404);
        }

        // For now, return assignments as applications
        $applications = Assignment::whereHas('projectRequest', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->with(['projectRequest', 'freelancer.user'])->get();

        return response()->json($applications);
    }

    public function getOngoingProjects()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['message' => 'Company profile not found'], 404);
        }

        $projects = ProjectRequest::where('company_id', $company->id)
            ->where('status', 'active')
            ->with(['assignments.freelancer.user', 'assignments.deliverables', 'assignments.statusUpdates'])
            ->get();

        return response()->json($projects);
    }

    public function getBilling()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['message' => 'Company profile not found'], 404);
        }

        // Placeholder for billing data
        $billing = [
            'outstanding_payments' => 0,
            'payment_history' => [],
            'total_spent' => 0,
        ];

        return response()->json($billing);
    }
}
