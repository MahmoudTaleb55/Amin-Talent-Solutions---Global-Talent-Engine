<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\JobRequestController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DeliverableController;
use App\Http\Controllers\StatusUpdateController;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
// Authentication routes (public) - Rate limited
Route::middleware('throttle:10,1')->group(function () {
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register/company', [AuthController::class, 'registerCompany']);
// Magic login for admin/CEO (secured via env secrets) - DISABLED by default
if (env('ENABLE_MAGIC_API', false)) {
    Route::post('auth/magic/{role}', [AuthController::class, 'magicLogin']);
}
});
Route::get('auth/verify-email/{token}', [AuthController::class, 'verifyEmail']);

    // Two-factor authentication routes
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('auth/2fa/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable']);
        Route::post('auth/2fa/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable']);
    });
    // 2FA confirm supports both logged-in and pending-login users
    Route::post('auth/2fa/confirm', [\App\Http\Controllers\TwoFactorController::class, 'confirm']);

    // Email verification resend
    Route::post('auth/resend-verification', [AuthController::class, 'resendVerification']);

    // Protected routes (require authentication)
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        // Return current user for auto-login via token
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Shared authenticated routes (all roles)
        Route::apiResource('assignments', AssignmentController::class)->only(['index','show'])->parameters(['assignments' => 'id']);
        Route::apiResource('deliverables', DeliverableController::class)->only(['index','show'])->parameters(['deliverables' => 'id']);
        Route::get('deliverables/{id}/download', [DeliverableController::class, 'downloadFile']);

        // Admin-only routes (allow CEO as well)
        Route::middleware('role:admin,ceo')->group(function () {
            Route::apiResource('admins', AdminController::class)->parameters(['admins' => 'id']);
            Route::get('analytics', [AdminController::class, 'analytics']);
            Route::get('analytics/user-growth', [AdminController::class, 'userGrowth']);
            Route::get('analytics/job-distribution', [AdminController::class, 'jobStatusDistribution']);
            Route::get('analytics/monthly-jobs', [AdminController::class, 'monthlyJobStats']);
            // Users list for admin
            Route::get('users', [AdminController::class, 'users']);
            // Allow admin to list job requests without company role middleware
            Route::get('job-requests', [JobRequestController::class, 'index']);
            // Admin moderation endpoints
            Route::post('job-requests/{id}/approve', [JobRequestController::class, 'approve']);
            Route::post('job-requests/{id}/reject', [JobRequestController::class, 'reject']);
        });

        // Company-only routes
        Route::middleware('role:company')->group(function () {
            Route::apiResource('companies', CompanyController::class)->parameters(['companies' => 'id']);
            Route::apiResource('job-requests', JobRequestController::class)->parameters(['job-requests' => 'id']);
            // Moderation endpoints
            Route::post('job-requests/{id}/approve', [JobRequestController::class, 'approve']);
            Route::post('job-requests/{id}/reject', [JobRequestController::class, 'reject']);
            // Company can create/update/delete assignments; index/show provided globally
            Route::apiResource('assignments', AssignmentController::class)->only(['store','update','destroy'])->parameters(['assignments' => 'id']);
        });

        // Freelancer-only routes
        Route::middleware('role:freelancer')->group(function () {
            Route::apiResource('freelancers', FreelancerController::class)->parameters(['freelancers' => 'id']);
            Route::get('freelancer/assignments', [FreelancerController::class, 'getAssignments']);
            Route::post('freelancer/assignments/{assignment}/status', [FreelancerController::class, 'updateStatus']);
            Route::post('freelancer/assignments/{assignment}/deliver', [FreelancerController::class, 'submitDeliverable']);
            // Allow freelancers to create/update/delete deliverables; index/show provided globally above
            Route::apiResource('deliverables', DeliverableController::class)->only(['store','update','destroy'])->parameters(['deliverables' => 'id']);
            Route::apiResource('status-updates', StatusUpdateController::class)->parameters(['status-updates' => 'id']);
        });
    });

    // Public read-only routes (for cross-role access)
    Route::apiResource('companies', CompanyController::class)->only(['index', 'show']);
    Route::apiResource('freelancers', FreelancerController::class)->only(['index', 'show']);
    Route::apiResource('job-requests', JobRequestController::class)->only(['index', 'show']);
        Route::apiResource('status-updates', StatusUpdateController::class)->only(['index', 'show']);
});
