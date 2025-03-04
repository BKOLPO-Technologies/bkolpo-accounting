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



        Route::get('/out', [StockController::class, 'Out'])->name('stock.out');
        Route::get('/out/{id}', [StockController::class, 'OutView'])->name('stock.out.view');
        Route::get('/in', [StockController::class, 'In'])->name('stock.in');
        Route::get('/in/{id}', [StockController::class, 'InView'])->name('stock.in.view');
    });
});

require __DIR__.'/auth.php';