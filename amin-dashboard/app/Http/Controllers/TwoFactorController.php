<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class TwoFactorController extends Controller
{
    // Enable TOTP: generate a secret and return otpauth URL for QR
    public function enable(Request $request)
    {
        $user = $request->user();

        $secret = strtoupper(Str::random(32)); // placeholder secret; replace with real TOTP lib
        $issuer = urlencode('Amin Talent Solutions');
        $label = urlencode($user->email);
        $otpauth = "otpauth://totp/{$issuer}:{$label}?secret={$secret}&issuer={$issuer}&algorithm=SHA1&digits=6&period=30";

        $user->two_factor_secret = $secret;
        $user->two_factor_enabled = true; // mark as enabled but not confirmed
        $user->two_factor_confirmed_at = null;
        $user->save();

        return response()->json([
            'otpauth_url' => $otpauth,
            'secret' => $secret,
        ]);
    }

    // Confirm 2FA with a code; issue token if confirming via pending login
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'two_factor_session' => 'nullable|string',
            'device_name' => 'nullable|string',
        ]);

        $user = $request->user();

        // If confirming from login flow (no user yet), resolve from cache session
        if (!$user && $request->filled('two_factor_session')) {
            $userId = Cache::get('2fa_session:' . $request->two_factor_session);
            if (!$userId) {
                return response()->json(['message' => 'Invalid or expired 2FA session'], 400);
            }
            $user = User::find($userId);
        }

        if (!$user || !$user->two_factor_secret) {
            return response()->json(['message' => '2FA is not enabled for this account'], 400);
        }

        // TODO: Replace with real TOTP verification; accept 000000 as dev code
        $code = $request->code;
        $isValid = ($code === '000000');

        if (!$isValid) {
            return response()->json(['message' => 'Invalid 2FA code'], 422);
        }

        // Mark confirmed
        if (!$user->two_factor_confirmed_at) {
            $user->two_factor_confirmed_at = now();
            $user->save();
        }

        // If came from login, issue token now
        if ($request->filled('two_factor_session')) {
            Cache::forget('2fa_session:' . $request->two_factor_session);
            $deviceName = $request->input('device_name', 'web-browser');
            $token = $user->createToken($deviceName)->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json(['message' => 'Two-factor authentication confirmed']);
    }

    // Disable 2FA
    public function disable(Request $request)
    {
        $user = $request->user();
        $user->two_factor_secret = null;
        $user->two_factor_enabled = false;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return response()->json(['message' => 'Two-factor authentication disabled']);
    }
}