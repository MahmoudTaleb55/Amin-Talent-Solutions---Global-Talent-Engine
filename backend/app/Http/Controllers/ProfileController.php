<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->only([
            'name','bio','phone','location','linkedin_url','website','skills','years_experience','hourly_rate','achievements'
        ]);

        if (isset($data['achievements']) && is_array($data['achievements'])) {
            $data['achievements'] = json_encode($data['achievements']);
        }

        $user->fill($data);
        $user->save();

        if (class_exists('\App\Models\AuditLog')) {
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => 'profile_updated',
                'meta' => []
            ]);
        }

        return response()->json(['message' => 'Profile updated', 'user' => $user]);
    }

    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|max:5120', // max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid avatar', 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $file = $request->file('avatar');
        $path = $file->store('avatars', 'public');
        $url = Storage::disk('public')->url($path);

        $user->avatar = $url;
        $user->save();

        if (class_exists('\App\Models\AuditLog')) {
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => 'avatar_uploaded',
                'meta' => ['avatar' => $url]
            ]);
        }

        return response()->json(['message' => 'Avatar uploaded', 'avatar' => $url]);
    }

    public function uploadResume(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'resume' => 'required|file|mimes:pdf,doc,docx,txt|max:10240', // up to 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid resume', 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $file = $request->file('resume');
        $path = $file->store('resumes', 'public');
        $url = Storage::disk('public')->url($path);

        $user->resume = $url;
        $user->save();

        if (class_exists('\App\Models\AuditLog')) {
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => 'resume_uploaded',
                'meta' => ['resume' => $url]
            ]);
        }

        return response()->json(['message' => 'Resume uploaded', 'resume' => $url]);
    }
}
