<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Inventory\ClientController;
use App\Http\Controllers\Backend\Inventory\PurchaseController;
use App\Http\Controllers\Backend\Inventory\SalesController;
use App\Http\Controllers\Backend\Inventory\QuotationController;
use App\Http\Controllers\Backend\Inventory\WorkOrderController;
use App\Http\Controllers\Backend\Inventory\IncomingChalanController;
use App\Http\Controllers\Backend\Inventory\OutComingChalanController;

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

    /* ==================== supplier =================== */
    Route::prefix('client')->group(function () {
        Route::get('/', [ClientController::class, 'AdminClientIndex'])->name('admin.client.index');
        Route::get('/create', [ClientController::class, 'AdminClientCreate'])->name('admin.client.create');
        Route::post('/storeClient', [ClientController::class, 'AdminClientStore'])->name('admin.client.store');
        Route::post('/storeClient2', [ClientController::class, 'AdminClientStore2'])->name('admin.client2.store');
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
        Route::get('/delete/{id}', [PurchaseController::class, 'destroy'])->name('admin.purchase.destroy');
        Route::get('/get-invoice-details/{id}', [PurchaseController::class, 'getInvoiceDetails']);
        Route::get('/print', [PurchaseController::class, 'Print'])->name('admin.purchase.print');
    });

    /* ==================== sales =================== */
    Route::prefix('sales')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('admin.sale.index');
        Route::get('/create', [SalesController::class, 'create'])->name('admin.sale.create');
        Route::post('/store', [SalesController::class, 'store'])->name('admin.sale.store');
        Route::get('/view/{id}', [SalesController::class, 'view'])->name('admin.sale.show');
        Route::get('/edit/{id}', [SalesController::class, 'edit'])->name('admin.sale.edit');
        Route::put('/update/{id}', [SalesController::class, 'update'])->name('admin.sale.update');
        Route::get('/delete/{id}', [SalesController::class, 'destroy'])->name('admin.sale.destroy');
        Route::get('/get-invoice-details/{id}', [SalesController::class, 'getInvoiceDetails']);

    });

    /* ==================== Incoming Chalan =================== */
    Route::prefix('chalan/incoming')->group(function () {
        Route::get('/', [IncomingChalanController::class, 'index'])->name('incoming.chalan.index');
        Route::get('/create', [IncomingChalanController::class, 'create'])->name('incoming.chalan.create');
        Route::post('/store', [IncomingChalanController::class, 'store'])->name('incoming.chalan.store');
        Route::get('/view/{id}', [IncomingChalanController::class, 'view'])->name('incoming.chalan.show');
        Route::get('/edit/{id}', [IncomingChalanController::class, 'edit'])->name('incoming.chalan.edit');
        Route::put('/update/{id}', [IncomingChalanController::class, 'update'])->name('incoming.chalan.update');
        Route::get('/delete/{id}', [IncomingChalanController::class, 'destroy'])->name('incoming.chalan.destroy');
    });

    /* ==================== Outcoming Chalan =================== */
    Route::prefix('chalan/outcoming')->group(function () {
        Route::get('/', [OutComingChalanController::class, 'index'])->name('outcoming.chalan.index');
        Route::get('/create', [OutComingChalanController::class, 'create'])->name('outcoming.chalan.create');
        Route::post('/store', [OutComingChalanController::class, 'store'])->name('outcoming.chalan.store');
        Route::get('/view/{id}', [OutComingChalanController::class, 'view'])->name('outcoming.chalan.show');
        Route::get('/edit/{id}', [OutComingChalanController::class, 'edit'])->name('outcoming.chalan.edit');
        Route::put('/update/{id}', [OutComingChalanController::class, 'update'])->name('outcoming.chalan.update');
        Route::get('/delete/{id}', [OutComingChalanController::class, 'destroy'])->name('outcoming.chalan.destroy');
    });


    // Quotation Routes
    Route::resource('quotations', QuotationController::class);
    Route::get('/quotations/delete/{id}', [QuotationController::class, 'destroy'])->name('quotations.destroy');
    // Work Order Routes
    Route::resource('workorders', WorkOrderController::class);
    Route::get('/delete/{id}', [WorkOrderController::class, 'destroy'])->name('workorders.destroy');
    Route::get('/workorders/invoice/{id}', [WorkOrderController::class, 'invoice'])->name('workorders.invoice');

});

require __DIR__.'/auth.php';