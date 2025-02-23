<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Inventory\ClientController;

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

    /* ==================== supplier =================== */
    Route::prefix('client')->group(function () {
        Route::get('/', [ClientController::class, 'AdminClientIndex'])->name('admin.client.index');
        Route::get('/create', [ClientController::class, 'AdminClientCreate'])->name('admin.client.create');
        Route::post('/storeClient', [ClientController::class, 'AdminClientStore'])->name('admin.client.store');
        Route::get('/view/{id}', [ClientController::class, 'AdminClientView'])->name('admin.client.view');
        Route::get('/edit/{id}', [ClientController::class, 'AdminClientEdit'])->name('admin.client.edit');
        Route::put('/update/{id}', [ClientController::class, 'AdminClientUpdate'])->name('admin.client.update');
        Route::delete('/delete/{id}', [ClientController::class, 'AdminClientDestroy'])->name('admin.client.destroy');
    });

});

require __DIR__.'/auth.php';