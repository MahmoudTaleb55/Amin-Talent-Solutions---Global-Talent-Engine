<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('role')) {
            $users = User::role($request->role)->with('roles')->get();
            return response()->json($users);
        }

        $users = User::with('roles')->get()->map(function($u) {
            return array_merge($u->toArray(), ['stripe_account_id' => $u->stripe_account_id ?? null]);
        });

        return response()->json($users);
    }
}
