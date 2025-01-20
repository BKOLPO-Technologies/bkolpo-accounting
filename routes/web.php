<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CompanyInformationController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\RoleController;



Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

/* =============== Start Admin Route  ============= */
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');

    /* ==================== Start HRM Management  All Routes =================== */

    /* ==================== End HRM Management  All Routes =================== */

    /* ==================== Start Company Information All Routes =================== */
    Route::prefix('company-information')->as('company-information.')->group(function () {
        Route::get('/', [CompanyInformationController::class, 'index'])->name('index');
        Route::get('edit/{id}', [CompanyInformationController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [CompanyInformationController::class, 'update'])->name('update');
    });
    /* ==================== End Company Information All Routes =================== */

    /* ==================== Start Role All Routes =================== */
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::get('delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
    /* ==================== End Role All Routes =================== */
});
/* =============== End Admin Route  ============= */


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
