<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Test route for authenticated user
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth routes
Route::prefix('hrm')->group(function () {
    Route::post('register', [AuthController::class, 'register']); 
    Route::middleware(['check.bk.token'])->post('login', [AuthController::class, 'login']); 
    Route::middleware(['auth:sanctum', 'check.bk.token'])->post('logout', [AuthController::class, 'logout']); 
    Route::middleware(['check.bk.token'])->post('forgot-password', [AuthController::class, 'sendResetLink']);
    Route::middleware(['check.bk.token'])->post('verify-code', [AuthController::class, 'verifyCode']);
    Route::middleware(['check.bk.token'])->post('reset-password', [AuthController::class, 'reset']);
});


// ==================== HRM (Staff Management) Routes ====================
Route::middleware('auth:sanctum','check.bk.token','HandleCors')->prefix('hrm')->group(function () {


    Route::options('{any}', function () {
        return response()->json([], 204);
    })->where('any', '.*');
    
});