<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return file_get_contents(public_path('index.html'));
});

// Dashboard routes with authentication and role-based access
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard/ceo', function () {
        return view('dashboards.ceo');
    })->middleware('role:ceo');

    Route::get('/dashboard/project-manager', function () {
        return view('dashboards.project_manager');
    })->middleware('role:project_manager');

    Route::get('/dashboard/service-leaders', function () {
        return view('dashboards.service_leaders');
    })->middleware('role:service_leaders');

    Route::get('/dashboard/employees', function () {
        return view('dashboards.employees');
    })->middleware('role:employee');

    Route::get('/dashboard/companies', function () {
        return view('dashboards.companies');
    })->middleware('role:company');
});

// API routes are handled separately in routes/api.php

// Secure one-time magic link access for Admin/CEO
// Usage: Issue a link like /magic/{token} where token = base64url(payload)."."base64url(hmacSHA256(base64url(payload), MAGIC_LINK_SECRET))
// payload JSON: {"role":"admin|ceo","iat":<unix_ts>,"exp":<unix_ts>,"jti":"<uuid>"}
Route::get('/magic/{token}', function ($token) {
    $secret = env('MAGIC_LINK_SECRET');
    if (!$secret) {
        abort(403, 'Magic link not configured');
    }

    $parts = explode('.', $token);
    if (count($parts) !== 2) {
        abort(403, 'Invalid token');
    }

    [$payloadB64, $sigB64] = $parts;

    $b64urlDecode = function ($data) {
        $data = strtr($data, '-_', '+/');
        return base64_decode($data);
    };
    $b64urlEncode = function ($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    };

    $expectedSig = $b64urlEncode(hash_hmac('sha256', $payloadB64, $secret, true));
    if (!hash_equals($expectedSig, $sigB64)) {
        abort(403, 'Invalid signature');
    }

    $payloadJson = $b64urlDecode($payloadB64);
    $payload = json_decode($payloadJson, true);
    if (!is_array($payload)) {
        abort(403, 'Invalid payload');
    }

    // Optional IP binding: if payload includes an ip, require match
    if (!empty($payload['ip'])) {
        if ((string)$payload['ip'] !== (string)request()->ip()) {
            abort(403, 'IP mismatch');
        }
    }

    $role = strtolower((string)($payload['role'] ?? ''));
    if (!in_array($role, ['admin', 'ceo'], true)) {
        abort(403, 'Invalid role');
    }

    $now = time();
    $iat = (int)($payload['iat'] ?? 0);
    $exp = (int)($payload['exp'] ?? 0);
    if ($iat > $now + 60 || $exp < $now) {
        abort(403, 'Token expired');
    }

    $jti = (string)($payload['jti'] ?? '');
    if ($jti === '') {
        abort(403, 'Invalid token id');
    }

    // One-time use enforcement
    $usedKey = 'used_magic_jti:' . $jti;
    if (!Cache::add($usedKey, 1, now()->addMinutes(10))) {
        abort(403, 'Token already used');
    }

    $user = User::where('role', $role)->first();
    if (!$user) {
        abort(404, ucfirst($role) . ' user not found');
    }

    $apiToken = $user->createToken('magic-link')->plainTextToken;

    // Auto-login by writing to localStorage and redirecting to SPA root
    $userJson = json_encode($user);
    $html = '<!doctype html><html><head><meta charset="utf-8"><title>Secure Access</title></head><body>' .
        '<script>(function(){' .
        'try{localStorage.setItem("auth_token", ' . json_encode($apiToken) . ');' .
        'localStorage.setItem("user", ' . $userJson . ');' .
        'window.location.replace("/");}catch(e){document.write("Login completed. Please reload the page.");}})();</script>' .
        '</body></html>';

    return response($html, 200)->header('Content-Type', 'text/html');
});
// Static, secured Admin portal (rate-limited). Uses ADMIN_PORTAL_KEY from env.
Route::match(['get','post'], '/admin-portal', function (Request $request) {
    $key = env('ADMIN_PORTAL_KEY');
    if (!$key) {
        return response('Admin portal not configured', 503);
    }

    if ($request->isMethod('post')) {
        // CSRF protection
        if (!hash_equals((string) $request->session()->token(), (string) $request->input('_token'))) {
            return response('Invalid request', 419);
        }
        $input = (string) $request->input('access_key');
        if (!hash_equals($key, $input)) {
            $token2 = csrf_token();
            $errorForm = <<<HTML
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Portal</title>
<style>body{font-family:sans-serif;background:#0b1220;color:#e5e7eb;display:flex;min-height:100vh;align-items:center;justify-content:center} .card{background:#111827;border:1px solid rgba(255,255,255,.08);padding:24px;border-radius:12px;min-width:340px;box-shadow:0 6px 24px rgba(0,0,0,.35)} input{width:100%;padding:12px;border-radius:8px;border:1px solid #334155;background:#0b1220;color:#e5e7eb;margin:8px 0} button{width:100%;padding:12px;background:#3b82f6;border:none;border-radius:8px;color:#fff;font-weight:600} .alert{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:10px} h3{margin:0 0 12px 0}</style>
</head><body>
<div class="card">
  <h3>Admin Portal</h3>
  <div class="alert">Access denied. Invalid passphrase.</div>
  <form method="post">
    <input type="hidden" name="_token" value="{$token2}">
    <label>Access Key</label>
    <input name="access_key" type="password" placeholder="Enter admin passphrase" required>
    <button type="submit">Try Again</button>
  </form>
</div>
</body></html>
HTML;
            return response($errorForm, 403)->header('Content-Type', 'text/html');
        }
        $user = User::where('role', 'admin')->first();
        if (!$user) {
            $email = env('ADMIN_EMAIL', 'admin@amin.local');
            $username = env('ADMIN_USERNAME', 'admin');
            if (User::where('username', $username)->exists()) {
                $username = 'admin_' . Str::lower(Str::random(6));
            }
            $existingByEmail = User::where('email', $email)->first();
            if ($existingByEmail) {
                $existingByEmail->role = 'admin';
                if (!$existingByEmail->email_verified_at) {
                    $existingByEmail->email_verified_at = now();
                }
                $existingByEmail->save();
                $user = $existingByEmail;
            } else {
                $user = User::create([
                    'username' => $username,
                    'email' => $email,
                    'password' => Hash::make(env('ADMIN_PASSWORD', Str::random(32))),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]);
            }
        }
        $apiToken = $user->createToken('admin-portal')->plainTextToken;
        $userJson = json_encode($user);
        $html = '<!doctype html><html><head><meta charset="utf-8"><title>Admin Access</title>' .
            '<style>body{font-family:sans-serif;background:#0b1220;color:#e5e7eb;display:flex;min-height:100vh;align-items:center;justify-content:center} .card{background:#111827;border:1px solid rgba(255,255,255,.08);padding:24px;border-radius:12px;min-width:340px;box-shadow:0 6px 24px rgba(0,0,0,.35)} a{color:#93c5fd}</style>' .
            '</head><body><div class="card"><h3>Signed in as Admin</h3><p>Redirecting to dashboard...</p><p><a href="/">Click here if not redirected</a></p></div>' .
            '<script>(function(){try{localStorage.setItem("auth_token", ' . json_encode($apiToken) . ');localStorage.setItem("user", ' . $userJson . ');setTimeout(function(){window.location.replace("/");}, 400);}catch(e){}})();</script>' .
            '</body></html>';
        return response($html, 200)->header('Content-Type', 'text/html');
    }

    // Render Blade view form
    return view('auth.admin_portal');
})->middleware('throttle:10,1');

// Static, secured CEO portal (rate-limited). Uses CEO_PORTAL_KEY from env.
Route::match(['get','post'], '/ceo-portal', function (Request $request) {
    $key = env('CEO_PORTAL_KEY');
    if (!$key) {
        return response('CEO portal not configured', 503);
    }

    if ($request->isMethod('post')) {
        // CSRF protection
        if (!hash_equals((string) $request->session()->token(), (string) $request->input('_token'))) {
            return response('Invalid request', 419);
        }
        $input = (string) $request->input('access_key');
        if (!hash_equals($key, $input)) {
            $token2 = csrf_token();
            $errorForm = <<<HTML
<!doctype html>
<html><head><meta charset="utf-8"><title>CEO Portal</title>
<style>body{font-family:sans-serif;background:#0b1220;color:#e5e7eb;display:flex;min-height:100vh;align-items:center;justify-content:center} .card{background:#111827;border:1px solid rgba(255,255,255,.08);padding:24px;border-radius:12px;min-width:340px;box-shadow:0 6px 24px rgba(0,0,0,.35)} input{width:100%;padding:12px;border-radius:8px;border:1px solid #334155;background:#0b1220;color:#e5e7eb;margin:8px 0} button{width:100%;padding:12px;background:#22c55e;border:none;border-radius:8px;color:#0b1220;font-weight:700} .alert{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:10px} h3{margin:0 0 12px 0}</style>
</head><body>
<div class="card">
  <h3>CEO Portal</h3>
  <div class="alert">Access denied. Invalid passphrase.</div>
  <form method="post">
    <input type="hidden" name="_token" value="{$token2}">
    <label>Access Key</label>
    <input name="access_key" type="password" placeholder="Enter CEO passphrase" required>
    <button type="submit">Try Again</button>
  </form>
</div>
</body></html>
HTML;
            return response($errorForm, 403)->header('Content-Type', 'text/html');
        }
        $user = User::where('role', 'ceo')->first();
        if (!$user) {
            $email = env('CEO_EMAIL', 'ceo@amin.local');
            $username = env('CEO_USERNAME', 'ceo');
            if (User::where('username', $username)->exists()) {
                $username = 'ceo_' . Str::lower(Str::random(6));
            }
            $existingByEmail = User::where('email', $email)->first();
            if ($existingByEmail) {
                $existingByEmail->role = 'ceo';
                if (!$existingByEmail->email_verified_at) {
                    $existingByEmail->email_verified_at = now();
                }
                $existingByEmail->save();
                $user = $existingByEmail;
            } else {
                $user = User::create([
                    'username' => $username,
                    'email' => $email,
                    'password' => Hash::make(env('CEO_PASSWORD', Str::random(32))),
                    'role' => 'ceo',
                    'email_verified_at' => now(),
                ]);
            }
        }
        $apiToken = $user->createToken('ceo-portal')->plainTextToken;
        $userJson = json_encode($user);
        $html = '<!doctype html><html><head><meta charset="utf-8"><title>CEO Access</title>' .
            '<style>body{font-family:sans-serif;background:#0b1220;color:#e5e7eb;display:flex;min-height:100vh;align-items:center;justify-content:center} .card{background:#111827;border:1px solid rgba(255,255,255,.08);padding:24px;border-radius:12px;min-width:340px;box-shadow:0 6px 24px rgba(0,0,0,.35)} a{color:#86efac}</style>' .
            '</head><body><div class="card"><h3>Signed in as CEO</h3><p>Redirecting to dashboard...</p><p><a href="/">Click here if not redirected</a></p></div>' .
            '<script>(function(){try{localStorage.setItem("auth_token", ' . json_encode($apiToken) . ');localStorage.setItem("user", ' . $userJson . ');setTimeout(function(){window.location.replace("/");}, 400);}catch(e){}})();</script>' .
            '</body></html>';
        return response($html, 200)->header('Content-Type', 'text/html');
    }

    // Render Blade view form
    return view('auth.ceo_portal');
})->middleware('throttle:10,1');

// Catch-all route to serve the SPA for non-API routes
// Preserve old static login URLs and redirect them to secure portals
Route::get('/admin-login.html', function() { return redirect('/admin-portal', 301); });
Route::get('/ceo-login.html', function() { return redirect('/ceo-portal', 301); });

Route::any('/{any}', function () {
    // Skip API routes
    if (request()->is('api/*')) {
        abort(404);
    }
    return file_get_contents(public_path('index.html'));
})->where('any', '.*');

// Internal secure endpoint to issue magic links (disabled by default)
Route::post('/internal/magic/issue', function (Request $request) {
    if (!env('ENABLE_MAGIC_ISSUER', false)) {
        abort(404);
    }

    $issuerKey = env('MAGIC_ISSUER_KEY');
    if (!$issuerKey || !hash_equals($issuerKey, (string)$request->header('X-Magic-Issuer-Key'))) {
        abort(403, 'Forbidden');
    }

    $allowed = array_filter(array_map('trim', explode(',', (string)env('MAGIC_ISSUER_ALLOWED_IPS'))));
    if (!empty($allowed) && !in_array($request->ip(), $allowed, true)) {
        abort(403, 'IP not allowed');
    }

    $role = strtolower((string)$request->input('role'));
    if (!in_array($role, ['admin', 'ceo'], true)) {
        return response()->json(['message' => 'Invalid role'], 422);
    }
    $minutes = max(1, min(60, (int)$request->input('minutes', 5)));
    $bindIp = filter_var($request->input('bind_ip', true), FILTER_VALIDATE_BOOLEAN);

    $payload = [
        'role' => $role,
        'iat' => time(),
        'exp' => time() + ($minutes * 60),
        'jti' => (string) Str::uuid(),
    ];
    if ($bindIp) {
        $payload['ip'] = $request->ip();
    }

    $secret = env('MAGIC_LINK_SECRET');
    if (!$secret) {
        return response()->json(['message' => 'MAGIC_LINK_SECRET not configured'], 500);
    }

    $b64urlEncode = function ($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    };

    $payloadJson = json_encode($payload, JSON_UNESCAPED_SLASHES);
    $payloadB64 = $b64urlEncode($payloadJson);
    $sig = hash_hmac('sha256', $payloadB64, $secret, true);
    $sigB64 = $b64urlEncode($sig);
    $token = $payloadB64 . '.' . $sigB64;

    return response()->json([
        'link' => url('/magic/' . $token),
        'expires_in_minutes' => $minutes,
        'bound_ip' => $bindIp ? $request->ip() : null,
        'jti' => $payload['jti'],
    ]);
});
