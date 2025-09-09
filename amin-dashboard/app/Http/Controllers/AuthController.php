<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Mail\CompanyVerificationEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'sometimes|string',
            'role' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $deviceName = $data['device_name'] ?? 'browser';
        $requestedRole = $data['role'] ?? null;

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Enforce role check on login
        if ($requestedRole && $requestedRole !== $user->role) {
            return response()->json(['message' => 'Selected role does not match your account role.'], 401);
        }

        // Check if email is verified
        if (!$user->email_verified_at) {
            return response()->json([
                'message' => 'Please verify your email address before logging in.',
                'requires_verification' => true,
                'email' => $user->email
            ], 403);
        }

        // If user has 2FA enabled and confirmed, return a temporary 2FA session instead of issuing a token
        if ($user->two_factor_enabled && $user->two_factor_confirmed_at) {
            $sessionId = Str::uuid()->toString();
            // Store user_id for 2FA verification for 5 minutes
            \Illuminate\Support\Facades\Cache::put('2fa_session:' . $sessionId, $user->id, now()->addMinutes(5));

            return response()->json([
                'requires_2fa' => true,
                'two_factor_session' => $sessionId,
                'message' => 'Two-factor authentication required.'
            ], 200);
        }

        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function registerCompany(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Create user with email verification token
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'company',
                'email_verification_token' => Str::random(64),
            ]);

            // Create company profile
            $company = Company::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'contact_person' => $request->contact_person,
                'phone' => $request->phone,
                'address' => $request->address,
                'industry' => $request->input('industry'),
                'company_size' => $request->input('company_size'),
                'description' => $request->input('description'),
            ]);

            DB::commit();

            // Send verification email
            try {
                Mail::to($user->email)->send(new CompanyVerificationEmail($user));
            } catch (\Exception $e) {
                // Log email sending error but don't fail registration
                Log::error('Email verification failed to send: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Company registered successfully. Please check your email for verification.',
                'user' => $user,
                'company' => $company,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Registration failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid verification token.',
                'success' => false
            ], 400);
        }

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Email already verified.',
                'success' => true
            ]);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        return response()->json([
            'message' => 'Email verified successfully! You can now log in to your account.',
            'success' => true
        ]);
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'If the account exists, a verification email will be sent.']);
        }
        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }

        // Re-generate token if missing
        if (!$user->email_verification_token) {
            $user->email_verification_token = Str::random(64);
            $user->save();
        }

        try {
            Mail::to($user->email)->send(new CompanyVerificationEmail($user));
        } catch (\Exception $e) {
            Log::error('Resend email failed: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Verification email resent if account exists.']);
    }

    // Return the authenticated user (for token-based auto-login)
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // Secure magic login for admin/CEO using environment-stored secret keys
    // Usage: POST /api/v1/auth/magic/{role} with JSON { key: "<secret>" }
    public function magicLogin(Request $request, string $role)
    {
        $role = strtolower($role);
        if (!in_array($role, ['admin', 'ceo'], true)) {
            return response()->json(['message' => 'Invalid role'], 400);
        }

        $providedKey = $request->input('key') ?? $request->header('X-Magic-Key');
        if (!$providedKey) {
            return response()->json(['message' => 'Missing key'], 400);
        }

        // Optional second factor: portal token header must match env, if configured
        $portalToken = $request->header('X-Portal-Token');
        $expectedPortal = env('PORTAL_PAGE_TOKEN');
        if ($expectedPortal && !$portalToken) {
            return response()->json(['message' => 'Missing portal token'], 400);
        }
        if ($expectedPortal && !hash_equals($expectedPortal, (string)$portalToken)) {
            Log::warning('Magic login portal token mismatch', ['role' => $role, 'ip' => $request->ip()]);
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $expected = $role === 'admin' ? env('ADMIN_MAGIC_KEY') : env('CEO_MAGIC_KEY');
        if (!$expected || !hash_equals($expected, $providedKey)) {
            Log::warning('Magic login failed', ['role' => $role, 'ip' => $request->ip()]);
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $user = User::where('role', $role)->first();
        if (!$user) {
            return response()->json(['message' => ucfirst($role) . ' user not found'], 404);
        }

        $token = $user->createToken('magic-link')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 200);
    }

}
