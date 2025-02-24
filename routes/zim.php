<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Inventory\ClientController;
use App\Http\Controllers\Backend\Inventory\PurchaseController;

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

    /* ==================== supplier =================== */
    Route::prefix('client')->group(function () {
        Route::get('/', [ClientController::class, 'AdminClientIndex'])->name('admin.client.index');
        Route::get('/create', [ClientController::class, 'AdminClientCreate'])->name('admin.client.create');
        Route::post('/storeClient', [ClientController::class, 'AdminClientStore'])->name('admin.client.store');
        Route::get('/view/{id}', [ClientController::class, 'AdminClientView'])->name('admin.client.view');
        Route::get('/edit/{id}', [ClientController::class, 'AdminClientEdit'])->name('admin.client.edit');
        Route::put('/update/{id}', [ClientController::class, 'AdminClientUpdate'])->name('admin.client.update');
        Route::get('/delete/{id}', [ClientController::class, 'AdminClientDestroy'])->name('admin.client.destroy');
    });

    /* ==================== purchase =================== */
    Route::prefix('purchase')->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('admin.purchase.index');
        Route::get('/create', [PurchaseController::class, 'AdminPurchaseCreate'])->name('admin.purchase.create');
        Route::post('/storeClient', [PurchaseController::class, 'AdminPurchaseStore'])->name('admin.purchase.store');
        Route::get('/view/{id}', [PurchaseController::class, 'AdminPurchaseView'])->name('admin.purchase.show');
        Route::get('/edit/{id}', [PurchaseController::class, 'AdminPurchaseEdit'])->name('admin.purchase.edit');
        Route::put('/update/{id}', [PurchaseController::class, 'AdminPurchaseUpdate'])->name('admin.purchase.update');
        Route::delete('/delete/{id}', [PurchaseController::class, 'AdminPurchaseDestroy'])->name('admin.purchase.destroy');
    });

});

require __DIR__.'/auth.php';