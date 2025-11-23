<?php

namespace App\Http\Controllers;

use App\Models\Freelancer;
use App\Models\ProjectRequest;
use App\Models\Assignment;
use Illuminate\Http\Request;

class ServiceLeaderController extends Controller
{
    public function getFreelancerPool()
    {
        $freelancers = Freelancer::with('user')->get();
        return response()->json($freelancers);
    }

    public function assignJob(Request $request)
    {
        $request->validate([
            'project_request_id' => 'required|exists:project_requests,id',
            'freelancer_id' => 'required|exists:freelancers,id',
            'notes' => 'nullable|string',
        ]);

        $assignment = Assignment::create([
            'project_request_id' => $request->project_request_id,
            'freelancer_id' => $request->freelancer_id,
            'status' => 'assigned',
            'assigned_at' => now(),
            'notes' => $request->notes,
        ]);

        return response()->json($assignment, 201);
    }

    public function getQualityControl()
    {
        // Get recent deliverables for review
        $deliverables = \App\Models\Deliverable::with(['assignment.projectRequest.company', 'assignment.freelancer.user'])
            ->latest()
            ->take(10)
            ->get();

        return response()->json($deliverables);
    }

    public function approveDeliverable($deliverableId)
    {
        $deliverable = \App\Models\Deliverable::findOrFail($deliverableId);
        // In real app, update status or mark as approved
        return response()->json(['message' => 'Deliverable approved']);
    }

    public function getServiceReports()
    {
        $reports = [
            'total_freelancers' => Freelancer::count(),
            'active_projects' => ProjectRequest::where('status', 'active')->count(),
            'total_assignments' => Assignment::count(),
            'completed_deliverables' => \App\Models\Deliverable::count(),
        ];

        return response()->json($reports);
    }
}
