<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FreelancerController extends Controller
{

    public function index(){

        $freelancers = Freelancer::with('user')->get();
        return response()->json($freelancers);
    }

    // Return assignments for the authenticated freelancer
    public function getAssignments()
    {
        $user = Auth::user();
        $freelancer = Freelancer::where('user_id', $user->id)->first();
        if (!$freelancer) {
            return response()->json([], 200);
        }

        $assignments = \App\Models\Assignment::with(['jobRequest', 'freelancer.user'])
            ->where('freelancer_id', $freelancer->id)
            ->orderByDesc('id')
            ->get();

        return response()->json($assignments);
    }

    public function store(Request $request){

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $freelancer = new Freelancer();
        $freelancer->user_id = $request->input('user_id');
        $freelancer->save();

        return response()->json($freelancer, 201);
    }

    public function show($id){

        $freelancer = Freelancer::find($id);
        if ($freelancer){

            return response()->json($freelancer);
        } else {
            return response()->json(['message' => 'Freelancer not found'], 404);
        }
    }

    public function update(Request $request, $id){

        $freelancer = Freelancer::find($id);
        $freelancer->user_id = $request->input('user_id');
        $freelancer->save();

        return response()->json($freelancer);
    }

    public function destroy($id){

        $freelancer = Freelancer::find($id);
        $freelancer->delete();
        return response()->json(null, 204);
    }
}