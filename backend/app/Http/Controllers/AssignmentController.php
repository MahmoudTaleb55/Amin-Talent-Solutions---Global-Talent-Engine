<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\ProjectRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'ceo'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'project_request_id' => 'required|exists:jobs,id',
            'freelancer_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $assignment = Assignment::create([
            'project_request_id' => $request->project_request_id,
            'freelancer_id' => $request->freelancer_id,
            'assigned_by' => $user->id,
            'status' => 'assigned',
        ]);

        // Update the project status
        $project = ProjectRequest::find($request->project_request_id);
        if ($project) {
            $project->status = 'in_progress';
            $project->save();
        }

        return response()->json($assignment, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        /** @var User $user */
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        $isAssignedFreelancer = $assignment->freelancer_id === $user->id;

        if (!$isAdmin && !$isAssignedFreelancer) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:in_progress,completed,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $assignment->update(['status' => $request->status]);

        // Also update the parent project's status if necessary
        if ($assignment->project && ($request->status === 'completed' || $request->status === 'rejected')) {
            $assignment->project->update(['status' => $request->status]);
        }

        return response()->json($assignment);
    }
}