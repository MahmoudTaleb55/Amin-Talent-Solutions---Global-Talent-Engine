<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        $query = Assignment::query()->with(['jobRequest', 'freelancer.user']);

        if ($user) {
            if ($user->role === 'company') {
                $company = \App\Models\Company::where('user_id', $user->id)->first();
                if ($company) {
                    $query->where('company_id', $company->id);
                } else {
                    return response()->json([], 200);
                }
            } elseif ($user->role === 'freelancer') {
                $freelancer = \App\Models\Freelancer::where('user_id', $user->id)->first();
                if ($freelancer) {
                    $query->where('freelancer_id', $freelancer->id);
                } else {
                    return response()->json([], 200);
                }
            }
            // Admin sees all assignments
        }

        $assignments = $query->orderByDesc('id')->get();
        return response()->json($assignments);
    }

    public function store(Request $request){

        $request->validate([
            'job_request_id' => 'required|exists:job_requests,id',
            'freelancer_id' => 'required|exists:freelancers,id',
            'company_id' => 'required|exists:companies,id',
        ]);

        $assignment = new Assignment();
        $assignment->job_request_id = $request->input('job_request_id');
        $assignment->freelancer_id = $request->input('freelancer_id');
        $assignment->company_id = $request->input('company_id');
        $assignment->save();

        return response()->json($assignment, 201);
    }

    public function show($id)
    {
        $assignment = Assignment::with(['jobRequest', 'freelancer.user'])->find($id);
        if ($assignment) {
            return response()->json($assignment);
        } else {
            return response()->json(['message' => 'Assignment not found'], 404);
        }
    }

    public function update(Request $request, $id){

        $request->validate([
            'job_request_id' => 'sometimes|exists:job_requests,id',
            'freelancer_id' => 'sometimes|exists:freelancers,id',
            'company_id' => 'sometimes|exists:companies,id',
        ]);

        $assignment = Assignment::find($id);
        if ($request->has('job_request_id')) {
            $assignment->job_request_id = $request->input('job_request_id');
        }
        if ($request->has('freelancer_id')) {
            $assignment->freelancer_id = $request->input('freelancer_id');
        }
        if ($request->has('company_id')) {
            $assignment->company_id = $request->input('company_id');
        }
        $assignment->save();

        return response()->json($assignment);
    }

    public function destroy($id){

        $assignment = Assignment::find($id);
        $assignment->delete();
        return response()->json(null, 204);
    }
}