<?php

namespace App\Http\Controllers;

use App\Models\ProjectRequest;
use App\Models\User; // for static analysis of role methods
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectRequestController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Handle filtering by status
        if ($request->has('status')) {
            if ($user->hasAnyRole(['admin', 'ceo', 'project_manager'])) {
                return response()->json(ProjectRequest::where('status', $request->status)->latest()->get());
            }
        }

        if ($user->hasRole('company')) {
            $company = $user->company;
            if (!$company) {
                return response()->json(['message' => 'No company associated with this user.'], 404);
            }
            $projects = ProjectRequest::where('company_id', $company->id)->latest()->get();
            return response()->json($projects);
        }

        // Allow oversight roles to view all projects
        if ($user->hasAnyRole(['admin', 'ceo', 'project_manager'])) {
            return response()->json(ProjectRequest::latest()->get());
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole('company') || !$user->company) {
            return response()->json(['message' => 'Unauthorized for creating a project request.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'nullable|numeric',
            'deadline' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $project = $user->company->projects()->create($request->all());

        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectRequest $projectRequest)
    {
        // Policy-based authorization would be added here later
        return response()->json($projectRequest);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectRequest $projectRequest)
    {
        // Policy-based authorization would be added here later
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'budget' => 'nullable|numeric',
            'deadline' => 'nullable|date',
            'status' => 'sometimes|in:pending_assignment,in_progress,completed,closed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $projectRequest->update($request->all());

        return response()->json($projectRequest);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectRequest $projectRequest)
    {
        // Policy-based authorization would be added here later
        $projectRequest->delete();
        return response()->json(null, 204);
    }
}