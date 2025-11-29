<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;

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

    public function destroy(Request $request, $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $this->authorize('delete', $portfolio);
        $portfolio->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
