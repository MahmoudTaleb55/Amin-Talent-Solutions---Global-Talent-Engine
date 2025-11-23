<?php

namespace App\Http\Controllers;

use App\Models\ProjectRequest;
use App\Models\Assignment;
use Illuminate\Http\Request;

class ProjectManagerController extends Controller
{
    public function getActiveProjects()
    {
        $projects = ProjectRequest::where('status', 'active')
            ->with(['company', 'assignments.freelancer.user', 'assignments.deliverables', 'assignments.statusUpdates'])
            ->get();

        return response()->json($projects);
    }

    public function allocateResource(Request $request, $projectId)
    {
        $request->validate([
            'freelancer_id' => 'required|exists:freelancers,id',
            'notes' => 'nullable|string',
        ]);

        $project = ProjectRequest::findOrFail($projectId);

        $assignment = Assignment::create([
            'project_request_id' => $projectId,
            'freelancer_id' => $request->freelancer_id,
            'status' => 'assigned',
            'assigned_at' => now(),
            'notes' => $request->notes,
        ]);

        return response()->json($assignment, 201);
    }

    public function getProjectReports()
    {
        $reports = ProjectRequest::with(['assignments'])->get()->map(function ($project) {
            return [
                'project' => $project,
                'total_assignments' => $project->assignments->count(),
                'completed_assignments' => $project->assignments->where('status', 'completed')->count(),
                'in_progress' => $project->assignments->where('status', 'in_progress')->count(),
            ];
        });

        return response()->json($reports);
    }

    public function getTeamPerformance()
    {
        // Placeholder for team performance metrics
        $performance = [
            'top_performers' => [],
            'project_completion_rate' => 0,
            'average_project_duration' => 0,
        ];

        return response()->json($performance);
    }
}
