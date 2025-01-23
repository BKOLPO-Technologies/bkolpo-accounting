<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\BankController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BranchController;
use App\Http\Controllers\Backend\LedgerController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\ProjectController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\PaymentMethodController;
use App\Http\Controllers\Backend\ExpenseCategoryController;
use App\Http\Controllers\Backend\CompanyInformationController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

/* =============== Start Admin Route  ============= */
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');

    /* ==================== Branch =================== */
    Route::prefix('branch')->as('branch.')->group(function () {
        Route::get('/', [BranchController::class, 'index'])->name('index');
        Route::get('/create', [BranchController::class, 'create'])->name('create');
        Route::post('/store', [BranchController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BranchController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BranchController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [BranchController::class, 'destroy'])->name('delete');
        Route::get('/view/{id}', [BranchController::class, 'show'])->name('show');
    }); 

    /* ==================== Bank  =================== */
    Route::prefix('bank')->as('bank.')->group(function () {
        Route::get('/', [BankController::class, 'index'])->name('index');
        Route::get('/create', [BankController::class, 'create'])->name('create');
        Route::post('/store', [BankController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BankController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BankController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [BankController::class, 'destroy'])->name('delete');
        Route::get('/view/{id}', [BankController::class, 'show'])->name('show');
    }); 

    /* ==================== payment method  =================== */
    Route::prefix('payment')->as('payment.')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
        Route::get('/create', [PaymentMethodController::class, 'create'])->name('create');
        Route::post('/store', [PaymentMethodController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PaymentMethodController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [PaymentMethodController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [PaymentMethodController::class, 'destroy'])->name('delete');
        Route::get('/view/{id}', [PaymentMethodController::class, 'show'])->name('show');
    }); 

    /* ==================== expense category  =================== */
    Route::prefix('expense-category')->as('expense-category.')->group(function () {
        Route::get('/', [ExpenseCategoryController::class, 'index'])->name('index');
        Route::get('/create', [ExpenseCategoryController::class, 'create'])->name('create');
        Route::post('/store', [ExpenseCategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ExpenseCategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ExpenseCategoryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ExpenseCategoryController::class, 'destroy'])->name('delete');
        Route::get('/view/{id}', [ExpenseCategoryController::class, 'show'])->name('show');
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
        Route::post('/store', [ProjectController::class, 'store'])->name('projects.store');
        Route::delete('/delete/{id}', [ProjectController::class, 'destroy'])->name('admin.projectDelete');
        Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('admin.projectEdit');
        Route::put('/admin/projects/{id}', [ProjectController::class, 'update'])->name('admin.projectUpdate');
    });

    /* ==================== supplier =================== */
    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'AdminSupplierIndex'])->name('admin.supplier.index');
        Route::get('/create', [SupplierController::class, 'AdminSupplierCreate'])->name('admin.supplier.create');
        Route::get('/view/{id}', [SupplierController::class, 'AdminSupplierView'])->name('admin.supplier.view');
        Route::get('/edit/{id}', [SupplierController::class, 'AdminSupplierEdit'])->name('admin.supplier.edit');
    });

    /* ==================== customers =================== */
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'AdminCustomerIndex'])->name('admin.customer.index');
        Route::get('/create', [CustomerController::class, 'AdminCustomerCreate'])->name('admin.customer.create');
        Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');

        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('admin.customer.edit');
        Route::put('/{id}/update', [CustomerController::class, 'update'])->name('admin.customer.update');
    });

    /* ==================== employee =================== */
    Route::prefix('employee')->group(function () {
        Route::get('/', [EmployeeController::class, 'AdminEmployeeIndex'])->name('admin.employee.index');
        Route::get('/add', [EmployeeController::class, 'AdminEmployeeAdd'])->name('admin.employee.add');
        Route::post('/store', [EmployeeController::class, 'store'])->name('admin.employee.store');
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
