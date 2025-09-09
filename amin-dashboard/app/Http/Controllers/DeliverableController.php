<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Deliverable;
use App\Http\Resources\DeliverableResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DeliverableController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        $query = Deliverable::query();

        if ($user) {
            if ($user->role === 'company') {
                $company = \App\Models\Company::where('user_id', $user->id)->first();
                if ($company) {
                    $query->whereHas('assignment', function ($q) use ($company) {
                        $q->where('company_id', $company->id);
                    });
                } else {
                    return response()->json([]);
                }
            } elseif ($user->role === 'freelancer') {
                $freelancer = \App\Models\Freelancer::where('user_id', $user->id)->first();
                if ($freelancer) {
                    $query->whereHas('assignment', function ($q) use ($freelancer) {
                        $q->where('freelancer_id', $freelancer->id);
                    });
                } else {
                    return response()->json([]);
                }
            }
            // Admin/CEO see all deliverables
        }

        $deliverables = $query->orderByDesc('id')->get();
        // Return plain array to match frontend .map expectations
        return response()->json($deliverables);
    }

    public function store(Request $request){

        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'content' => 'required|string',
            'submitted_on' => 'required|date',
            'status' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,jpeg,png,gif|max:10240', // 10MB max
        ]);

        $deliverable = new Deliverable();
        $deliverable->assignment_id = $request->input('assignment_id');
        $deliverable->content = $request->input('content');
        $deliverable->submitted_on = $request->input('submitted_on');
        $deliverable->status = $request->input('status', 'pending');
        // Handle file upload if a file is provided
        if ($request->hasFile('file') && $request->file('file')->isValid()){

            $file = $request->file('file');

            // Generate a unique filename with original extension
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Store the file in the deliverable disk
            $path = $file->storeAs(
                'assignment_' . $request->input('assignment_id'),
                $filename,
                'deliverable'
            );

            // Save the file path
            $deliverable->file_path = $path;
            $deliverable->file_name = $file->getClientOriginalName(); // Original file name
        }
        $deliverable->save();

        return new DeliverableResource($deliverable);
    }

    public function show($id){

        $deliverable = Deliverable::find($id);
        if ($deliverable){

            return new DeliverableResource($deliverable);
        } else {
            return response()->json(['message' => 'Deliverable not found'], 404);
        }
    }

    public function update(Request $request, $id){

        $request->validate([
            'assignment_id' => 'sometimes|exists:assignments,id',
            'content' => 'sometimes|string',
            'submitted_on' => 'sometimes|date',
            'status' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,jpeg,png,gif|max:10240', // 10MB max
        ]);

        $deliverable = Deliverable::find($id);
        if (!$deliverable) {
            return response()->json(['message' => 'Deliverable not found'], 404);
        }
        if ($request->has('content')) {
            $deliverable->content = $request->input('content');
        }
        if ($request->has('submitted_on')) {
            $deliverable->submitted_on = $request->input('submitted_on');
        }
        if ($request->has('status')) {
            $deliverable->status = $request->input('status');
        }
        // Handle file upload if a file is provided
        if ($request->hasFile('file') && $request->file('file')->isValid()){
            // Delete the old file if it exists
            if ($deliverable->file_path && Storage::disk('deliverable')->exists($deliverable->file_path)) {
                Storage::disk('deliverable')->delete($deliverable->file_path);
            }
            $file = $request->file('file');

            // Generate a unique filename with original extension
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Store the file in the deliverable disk
            $path = $file->storeAs(
                'assignment_' . $request->input('assignment_id'),
                $filename,
                'deliverable'
            );

            // Save the file path
            $deliverable->file_path = $path;
            $deliverable->file_name = $file->getClientOriginalName(); // Original file name
        }
        $deliverable->save();

        return new DeliverableResource($deliverable);
    }

    public function destroy($id){

        $deliverable = Deliverable::find($id);
        if (!$deliverable) {

            return response()->json(['message' => 'Deliverable not found'], 404);
        }

        // Delete the associated file if it exists
        if ($deliverable->file_path && Storage::disk('deliverable')->exists($deliverable->file_path)) {
            Storage::disk('deliverable')->delete($deliverable->file_path);
        }
        $deliverable->delete();
        return response()->json(null, 204);
    }

    /**
     * Download the file associated with a deliverable
     *
     * @param int $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile($id)
    {
        $deliverable = Deliverable::find($id);
        
        if (!$deliverable) {
            return response()->json(['message' => 'Deliverable not found'], 404);
        }
        
        if (!$deliverable->file_path) {
            return response()->json(['message' => 'No file associated with this deliverable'], 404);
        }
        
        if (!Storage::disk('deliverable')->exists($deliverable->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        
        // Get the file name from the path
        $fileName = basename($deliverable->file_path);
        
        // Return the file as a download (manually stream since Filesystem::download is unavailable)
        $fullPath = Storage::disk('deliverable')->path($deliverable->file_path);
        if (!file_exists($fullPath)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        return response()->download($fullPath, $fileName);
    }
}