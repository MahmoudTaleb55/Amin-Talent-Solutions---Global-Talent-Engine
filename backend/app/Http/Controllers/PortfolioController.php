<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('freelancer') || $user->hasRole('company')) {
            $items = Portfolio::where('user_id', $user->id)->get();
        } else {
            $items = Portfolio::all();
        }
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'url' => 'nullable|url',
        ]);

        $user = $request->user();
        $portfolio = Portfolio::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
            'attachments' => $request->attachments ?? null,
        ]);

        return response()->json($portfolio, 201);
    }

    public function uploadAttachment(Request $request, $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $this->authorize('update', $portfolio);

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('portfolios', 'public');

        $attachments = $portfolio->attachments ?? [];
        if (!is_array($attachments)) {
            $attachments = json_decode($attachments, true) ?: [];
        }

        $attachments[] = Storage::url($path);
        $portfolio->attachments = $attachments;
        $portfolio->save();

        return response()->json(['path' => Storage::url($path), 'portfolio' => $portfolio]);
    }

    public function destroy(Request $request, $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $this->authorize('delete', $portfolio);
        $portfolio->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
