<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BranchController;
use App\Http\Controllers\Backend\LedgerController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\ProjectController;
use App\Http\Controllers\Backend\BankCashController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\CompanyInformationController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

/* =============== Start Admin Route  ============= */
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');

    /* ==================== Branch =================== */
    Route::prefix('branch')->as('branch.admin.')->group(function () {
        Route::get('/', [BranchController::class, 'AdminBranch'])->name('branch'); // branch.admin.branch
        Route::get('/create', [BranchController::class, 'AdminCreate'])->name('create'); // branch.admin.create
        Route::post('/store', [BranchController::class, 'store'])->name('branch.store'); // branch.admin.branch.store
        Route::get('/trashed', [BranchController::class, 'AdminTrashed'])->name('trashed'); // branch.admin.trashed
        Route::delete('/delete/{branch}', [BranchController::class, 'destroy'])->name('destroy'); // branch.admin.destroy
        Route::get('/{branch}/edit', [BranchController::class, 'edit'])->name('edit'); // branch.admin.edit
        Route::put('/{branch}/update', [BranchController::class, 'update'])->name('update'); // branch.admin.update
    }); 

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

    /* ==================== Bank Cash =================== */
    Route::prefix('bank-cash')->group(function () {
        Route::get('/invoice', [BankCashController::class, 'AdminBankCashInvoice'])->name('admin.bankcash.invoice');
        Route::get('/', [BankCashController::class, 'AdminBankCash'])->name('admin.bankcash');
        Route::get('/create', [BankCashController::class, 'AdminBankCashCreate'])->name('admin.bankcash.create');
        Route::get('/trashed', [BankCashController::class, 'AdminBankCashTrashed'])->name('admin.bankcash.trashed');
    });

    /* ==================== Invoice =================== */
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'AdminInvoiceIndex'])->name('admin.invoice');
        
        Route::get('/create', [InvoiceController::class, 'AdminInvoiceCreate'])->name('admin.invoiceCreate');
    });

    /* ==================== Projects =================== */
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'AdminProjectIndex'])->name('admin.project');
        Route::get('/create', [ProjectController::class, 'AdminProjectCreate'])->name('admin.projectCreate');
        Route::get('/details/1', [InvoiceController::class, 'AdminInvoiceDetails'])->name('admin.invoiceDetails');
    });

    /* ==================== supplier =================== */
    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'AdminSupplierIndex'])->name('admin.supplier.index');
        Route::get('/create', [SupplierController::class, 'AdminSupplierCreate'])->name('admin.supplier.create');
        Route::get('/view/{id}', [SupplierController::class, 'AdminSupplierView'])->name('admin.supplier.view');
        Route::get('/edit/{id}', [SupplierController::class, 'AdminSupplierEdit'])->name('admin.supplier.edit');
    });

    /* ==================== Transactions =================== */
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'AdminTransactionIndex'])->name('admin.transaction.index');
        Route::get('/add', [TransactionController::class, 'AdminTransactionAdd'])->name('admin.transaction.add');
        Route::get('/transfer', [TransactionController::class, 'AdminTransactionTransfer'])->name('admin.transaction.transfer');
        Route::get('/income', [TransactionController::class, 'AdminTransactionIncome'])->name('admin.transaction.income');
        Route::get('/expense', [TransactionController::class, 'AdminTransactionExpense'])->name('admin.transaction.expense');
    });

    /* ==================== Company Information =================== */
    Route::prefix('company-information')->as('company-information.')->group(function () {
        Route::get('/', [CompanyInformationController::class, 'index'])->name('index');
        Route::get('edit/{id}', [CompanyInformationController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [CompanyInformationController::class, 'update'])->name('update');
    });

    /* ==================== Role and User Management =================== */
    Route::resource('roles', RoleController::class) ->middleware([
        'can:role-list',   
        'can:role-create',  
        'can:role-edit',   
        'can:role-delete',
    ]);
    Route::get('roles/delete/{id}', [RoleController::class, 'destroy'])->name('roles.delete');
    Route::resource('users', UserController::class);
    Route::get('users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
});

/* =============== End Admin Route  ============= */

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
