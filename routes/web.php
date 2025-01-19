<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AdminController;



Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

/* =============== Start Admin Route  ============= */
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');

    /* ==================== Start HRM Management  All Routes =================== */

    /* ==================== End HRM Management  All Routes =================== */

    /* ==================== Start Chat Management  All Routes =================== */
    Route::prefix('chat')->group(function () {
      

    });
});
/* =============== End Admin Route  ============= */


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
