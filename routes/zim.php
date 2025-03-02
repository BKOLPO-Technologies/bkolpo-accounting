<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Inventory\StockController;

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    /* ==================== Stock =================== */
    Route::prefix('stock')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('stock.index');
        // Route::get('/create', [OutComingChalanController::class, 'create'])->name('outcoming.chalan.create');
        // Route::post('/store', [OutComingChalanController::class, 'store'])->name('outcoming.chalan.store');
        Route::get('/view/{id}', [StockController::class, 'view'])->name('stock.show');
        // Route::get('/edit/{id}', [OutComingChalanController::class, 'edit'])->name('outcoming.chalan.edit');
        // Route::put('/update/{id}', [OutComingChalanController::class, 'update'])->name('outcoming.chalan.update');
        // Route::get('/delete/{id}', [OutComingChalanController::class, 'destroy'])->name('outcoming.chalan.destroy');
    });
});

require __DIR__.'/auth.php';