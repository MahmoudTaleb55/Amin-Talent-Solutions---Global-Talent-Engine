<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\StatusUpdate;
use Illuminate\Http\Request;

class StatusUpdateController extends Controller
{

    public function index(){

        $status = StatusUpdate::all();
        return response()->json($status);
    }

    public function store(Request $request){

        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'status' => 'required|string',
            'updated_on' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $status = new StatusUpdate();
        $status->assignment_id = $request->input('assignment_id');
        $status->status = $request->input('status');
        $status->updated_on = $request->input('updated_on');
        $status->notes = $request->input('notes');
        $status->save();

        return response()->json($status, 201);
    }

    public function show($id){

        $status = StatusUpdate::find($id);
        if ($status){

            return response()->json($status);
        } else {
            return response()->json(['message' => 'Status Update not found'], 404);
        }
    }

    public function update(Request $request, $id){

        $request->validate([
            'assignment_id' => 'sometimes|exists:assignments,id',
            'status' => 'sometimes|string',
            'updated_on' => 'sometimes|date',
            'notes' => 'nullable|string',
        ]);

        $status = StatusUpdate::find($id);
        if ($request->has('assignment_id')) {
            $status->assignment_id = $request->input('assignment_id');
        }
        if ($request->has('status')) {
            $status->status = $request->input('status');
        }
        if ($request->has('updated_on')) {
            $status->updated_on = $request->input('updated_on');
        }
        if ($request->has('notes')) {
            $status->notes = $request->input('notes');
        }
        $status->save();

        return response()->json($status);
    }

    public function destroy($id){

        $status = StatusUpdate::find($id);
        $status->delete();
        return response()->json(null, 204);
    }
}