<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Deliverable;
use App\Models\StatusUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FreelancerController extends Controller
{
    public function getAssignments()
    {
        $user = Auth::user();
        $freelancer = $user->freelancer;

        if (!$freelancer) {
            return response()->json(['message' => 'Freelancer profile not found'], 404);
        }

        $assignments = Assignment::where('freelancer_id', $freelancer->id)
            ->with(['projectRequest.company', 'deliverables', 'statusUpdates'])
            ->get();

        return response()->json($assignments);
    }

    public function submitDeliverable(Request $request, $assignmentId)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();
        $freelancer = $user->freelancer;

        $assignment = Assignment::where('id', $assignmentId)
            ->where('freelancer_id', $freelancer->id)
            ->first();

        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], 404);
        }

        $file = $request->file('file');
        $filePath = $file->store('deliverables', 'public');

        $deliverable = Deliverable::create([
            'assignment_id' => $assignmentId,
            'file_path' => $filePath,
            'description' => $request->description,
        ]);

        return response()->json($deliverable, 201);
    }

    public function postStatusUpdate(Request $request, $assignmentId)
    {
        $request->validate([
            'status' => 'required|in:in_progress,completed,on_hold,cancelled',
            'message' => 'nullable|string',
        ]);

        $user = Auth::user();
        $freelancer = $user->freelancer;

        $assignment = Assignment::where('id', $assignmentId)
            ->where('freelancer_id', $freelancer->id)
            ->first();

        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], 404);
        }

        $statusUpdate = StatusUpdate::create([
            'assignment_id' => $assignmentId,
            'status' => $request->status,
            'message' => $request->message,
        ]);

        return response()->json($statusUpdate, 201);
    }
}
