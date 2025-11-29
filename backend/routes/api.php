<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectRequestController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CEOController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectManagerController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Project Request Routes
    Route::apiResource('projects', ProjectRequestController::class);

    // Assignment Routes (scoped for simplicity, as we only need create and update for now)
    Route::post('assignments', [AssignmentController::class, 'store']);
    Route::put('assignments/{assignment}', [AssignmentController::class, 'update']);

    // User Route
    Route::get('users', [UserController::class, 'index']);

    // Freelancer Routes
    Route::middleware('role:freelancer')->group(function () {
        Route::get('freelancer/assignments', [FreelancerController::class, 'getAssignments']);
        Route::post('freelancer/assignments/{assignmentId}/deliverable', [FreelancerController::class, 'submitDeliverable']);
        Route::post('freelancer/assignments/{assignmentId}/status', [FreelancerController::class, 'postStatusUpdate']);
    });

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/users', [AdminController::class, 'getUsers']);
        Route::post('admin/users', [AdminController::class, 'createUser']);
        Route::put('admin/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('admin/users/{id}', [AdminController::class, 'deleteUser']);
        Route::get('admin/stats', [AdminController::class, 'getSystemStats']);
    });

    // CEO Routes
    Route::middleware('role:ceo')->group(function () {
        Route::get('ceo/overview', [CEOController::class, 'getCompanyOverview']);
        Route::get('ceo/financial-reports', [CEOController::class, 'getFinancialReports']);
        Route::get('ceo/user-management', [CEOController::class, 'getUserManagement']);
        Route::put('ceo/users/{id}/role', [CEOController::class, 'updateUserRole']);
        Route::get('ceo/system-settings', [CEOController::class, 'getSystemSettings']);
        Route::put('ceo/system-settings', [CEOController::class, 'updateSystemSettings']);
    });

    // Company Routes
    Route::middleware('role:company')->group(function () {
        Route::post('company/post-job', [CompanyController::class, 'postJob']);
        Route::get('company/my-jobs', [CompanyController::class, 'getMyJobs']);
        Route::get('company/applications', [CompanyController::class, 'getApplications']);
        Route::get('company/ongoing-projects', [CompanyController::class, 'getOngoingProjects']);
        Route::get('company/billing', [CompanyController::class, 'getBilling']);
    });

    // Project Manager Routes
    Route::middleware('role:project_manager')->group(function () {
        Route::get('project/active-projects', [ProjectManagerController::class, 'getActiveProjects']);
        Route::post('project/{projectId}/allocate', [ProjectManagerController::class, 'allocateResource']);
        Route::get('project/reports', [ProjectManagerController::class, 'getProjectReports']);
        Route::get('project/team-performance', [ProjectManagerController::class, 'getTeamPerformance']);
    });

    // Profile routes
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update']);
    Route::post('profile/avatar', [\App\Http\Controllers\ProfileController::class, 'uploadAvatar']);
    Route::post('profile/resume', [\App\Http\Controllers\ProfileController::class, 'uploadResume']);
});
