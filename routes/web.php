<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\CompanyInformationController;
use App\Http\Controllers\Backend\UserController;



Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

/* =============== Start Admin Route  ============= */
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');

    /* ==================== Branch =================== */
    Route::prefix('branch')->group(function () {
        Route::get('/', [BranchController::class, 'AdminBranch'])->name('admin.branch');
        Route::get('/create', [BranchController::class, 'AdminCreate'])->name('admin.create');
        Route::get('/trashed', [BranchController::class, 'AdminTrashed'])->name('admin.trashed');
    });
    /* ==================== ========= =================== */

    /* ==================== Ledger =================== */
    //ledger/group
    Route::prefix('ledger/group')->group(function () {
        Route::get('/', [LedgerController::class, 'AdminLedgerGroup'])->name('admin.ledgergroup');
        Route::get('/create', [LedgerController::class, 'AdminLedgerGroupCreate'])->name('admin.ledgergroupcreate');
        Route::get('/trashed', [LedgerController::class, 'AdminLedgerGroupTrashed'])->name('admin.ledgergrouptrashed');
    });

    //ledger/name
    Route::prefix('ledger/name')->group(function () {
        Route::get('/', [LedgerController::class, 'AdminLedgerName'])->name('admin.ledgername');
        Route::get('/create', [LedgerController::class, 'AdminLedgerNameCreate'])->name('admin.ledgernamecreate');
        Route::get('/trashed', [LedgerController::class, 'AdminLedgerNameTrashed'])->name('admin.ledgernametrashed');
    });
    /* ==================== ========= =================== */

    /* ==================== Bank Cash =================== */
    Route::prefix('bank-cash')->group(function () {
        Route::get('/invoice', [BankCashController::class, 'AdminBankCashInvoice'])->name('admin.bankcash.invoice');
        Route::get('/', [BankCashController::class, 'AdminBankCash'])->name('admin.bankcash');
        Route::get('/create', [BankCashController::class, 'AdminBankCashCreate'])->name('admin.bankcash.create');
        Route::get('/trashed', [BankCashController::class, 'AdminBankCashTrashed'])->name('admin.bankcash.trashed');
    });
    /* ==================== ========= =================== */

    /* ==================== Invoice =================== */
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'AdminInvoiceIndex'])->name('admin.invoice');
        Route::get('/1', [InvoiceController::class, 'AdminInvoiceDetails'])->name('admin.invoiceDetails');
        Route::get('/create', [InvoiceController::class, 'AdminInvoiceCreate'])->name('admin.invoiceCreate');    
    });
    /* ==================== ========= =================== */

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
