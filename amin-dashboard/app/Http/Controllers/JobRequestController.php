<?php

namespace App\Http\Controllers;

use App\Models\JobRequest;
use Illuminate\Http\Request;

class JobRequestController extends Controller
{

    public function index(Request $request){
        // Admin can see all, company sees own, public sees approved
        $query = JobRequest::query();

        $user = $request->user();
        if ($user) {
            if ($user->role === 'company') {
                $company = \App\Models\Company::where('user_id', $user->id)->first();
                if ($company) {
                    $query->where('company_id', $company->id);
                } else {
                    return response()->json([], 200);
                }
            }
            // Admin sees all
        } else {
            // Public (unauthenticated) sees only approved
            $query->where('status', 'approved');
        }

        $jobRequests = $query->orderByDesc('created_at')->get();
        return response()->json($jobRequests);
    }

    public function store(Request $request){

        $request->validate([
            'description' => 'required|string',
            'deadline' => 'required|date',
            'status' => 'nullable|string',
        ]);

        // Derive company_id from authenticated user
        $user = $request->user();
        $company = \App\Models\Company::where('user_id', $user->id)->first();
        if (!$company) {
            return response()->json(['message' => 'Company profile not found for this user'], 422);
        }

        $jobRequest = new JobRequest();
        $jobRequest->company_id = $company->id;
        $jobRequest->description = $request->input('description');
        $jobRequest->deadline = $request->input('deadline');

        // Normalize status to DB enum: pending/approved/rejected/completed
        $status = strtolower((string) $request->input('status', 'pending'));
        $map = [
            'open' => 'pending',
            'draft' => 'pending',
        ];
        $allowed = ['pending', 'approved', 'rejected', 'completed'];
        $normalized = $map[$status] ?? $status;
        if (!in_array($normalized, $allowed, true)) {
            $normalized = 'pending';
        }
        $jobRequest->status = $normalized;
        $jobRequest->save();

        return response()->json($jobRequest, 201);
    }

    public function show($id){

        $jobRequest = JobRequest::find($id);
        if ($jobRequest){

            return response()->json($jobRequest);
        } else {
            return response()->json(['message'=>'Job Request not found'], 404);
        }
    }

    public function update(Request $request, $id){

        $jobRequest = JobRequest::find($id);
        if (!$jobRequest) {
            return response()->json(['message' => 'Job Request not found'], 404);
        }
        // Only allow update if this job belongs to the authenticated company
        $user = $request->user();
        $company = \App\Models\Company::where('user_id', $user->id)->first();
        if (!$company || (int)$jobRequest->company_id !== (int)$company->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'description' => 'sometimes|required|string',
            'deadline' => 'sometimes|required|date',
            'status' => 'sometimes|nullable|string|in:pending,approved,rejected,completed',
        ]);

        $jobRequest->description = $request->input('description', $jobRequest->description);
        $jobRequest->deadline = $request->input('deadline', $jobRequest->deadline);
        $jobRequest->status = $request->input('status', $jobRequest->status);
        $jobRequest->save();

        return response()->json($jobRequest);
    }

    public function destroy($id){

        $jobRequest = JobRequest::find($id);
        $jobRequest->delete();

        return response()->json(null, 204);
    }

    // Admin moderation helpers (company creates, admin moderates). For now allow company self-approval if desired.
    public function approve(Request $request, $id)
    {
        $jobRequest = JobRequest::find($id);
        if (!$jobRequest) return response()->json(['message' => 'Job Request not found'], 404);
        $jobRequest->status = 'approved';
        $jobRequest->save();
        return response()->json($jobRequest);
    }

    public function reject(Request $request, $id)
    {
        $jobRequest = JobRequest::find($id);
        if (!$jobRequest) return response()->json(['message' => 'Job Request not found'], 404);
        $jobRequest->status = 'rejected';
        $jobRequest->save();
        return response()->json($jobRequest);
    }
}