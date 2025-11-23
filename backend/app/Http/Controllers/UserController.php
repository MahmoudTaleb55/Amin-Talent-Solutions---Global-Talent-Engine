<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('role')) {
            $users = User::role($request->role)->get();
            return response()->json($users);
        }

        return response()->json(User::all());
    }
}
