<?php

use App\Models\LedgerSubGroup;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\BankController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BranchController;
use App\Http\Controllers\Backend\LedgerController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\JournalController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\LedgerGroupController;
use App\Http\Controllers\Backend\PaymentMethodController;
use App\Http\Controllers\Backend\Inventory\UnitController;
use App\Http\Controllers\Backend\LedgerSubGroupController;
use App\Http\Controllers\Backend\Inventory\SalesController;
use App\Http\Controllers\Backend\Inventory\StockController;
use App\Http\Controllers\Backend\Inventory\ClientController;
use App\Http\Controllers\Backend\Inventory\ProductController;
use App\Http\Controllers\Backend\Inventory\ProjectController;
use App\Http\Controllers\Backend\CompanyInformationController;
use App\Http\Controllers\Backend\Inventory\CategoryController;
use App\Http\Controllers\Backend\Inventory\PurchaseController;
use App\Http\Controllers\Backend\Inventory\QuotationController;
use App\Http\Controllers\Backend\Inventory\WorkOrderController;
use App\Http\Controllers\Backend\Inventory\SalePaymentController;
use App\Http\Controllers\Backend\Inventory\SaleReceiptController;
use App\Http\Controllers\Backend\Inventory\IncomingChalanController;
use App\Http\Controllers\Backend\Inventory\OutComingChalanController;
use App\Http\Controllers\Backend\Inventory\ProductSaleReceiveController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

/* =============== Start Admin Route  ============= */
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'AdminDashboard'])->middleware('can:dashboard-menu')->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');

    /* ==================== Branch =================== */
    Route::prefix('branch')->as('branch.')->group(function () {
        Route::get('/', [BranchController::class, 'index'])->name('index')->middleware('can:branch-list');
        Route::get('/create', [BranchController::class, 'create'])->name('create')->middleware('can:branch-create');
        Route::post('/store', [BranchController::class, 'store'])->name('store');
        Route::post('/store2', [BranchController::class, 'store2'])->name('store2');
        Route::get('/edit/{id}', [BranchController::class, 'edit'])->name('edit')->middleware('can:branch-edit');
        Route::post('/update/{id}', [BranchController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [BranchController::class, 'destroy'])->name('delete')->middleware('can:branch-delete');
        Route::get('/view/{id}', [BranchController::class, 'show'])->name('show')->middleware('can:branch-view');
    }); 

    Route::prefix('company')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('company.index')->middleware('can:company-list');
        Route::get('/create', [CompanyController::class, 'create'])->name('company.create')->middleware('can:company-create');
        Route::post('/store', [CompanyController::class, 'store'])->name('company.store');
        Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit')->middleware('can:company-edit');
        Route::post('/update/{id}', [CompanyController::class, 'update'])->name('company.update');
        Route::get('/delete/{id}', [CompanyController::class, 'destroy'])->name('company.delete')->middleware('can:company-delete');
        Route::get('/view/{id}', [CompanyController::class, 'show'])->name('company.show')->middleware('can:company-view');

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

    /* ==================== ledger category  =================== */
    Route::prefix('ledger')->as('ledger.')->group(function () {
        Route::get('/', [LedgerController::class, 'index'])->name('index')->middleware('can:ledger-list');
        Route::get('/create', [LedgerController::class, 'create'])->name('create')->middleware('can:ledger-create');
        Route::post('/store', [LedgerController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [LedgerController::class, 'edit'])->name('edit')->middleware('can:ledger-edit');
        Route::post('/update/{id}', [LedgerController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [LedgerController::class, 'destroy'])->name('delete')->middleware('can:ledger-delete');
        Route::get('/view/{id}', [LedgerController::class, 'show'])->name('show')->middleware('can:ledger-view');
        Route::get('/ledger/import/format', [LedgerController::class, 'downloadFormat'])->name('import.format');
        Route::post('/import', [LedgerController::class, 'import'])->name('import');

        
        
    });

    /* ==================== ledger group category  =================== */
    Route::prefix('ledger-group')->as('ledger.group.')->group(function () {
        Route::get('/', [LedgerGroupController::class, 'index'])->name('index')->middleware('can:ledger-group-list');
        Route::get('/create', [LedgerGroupController::class, 'create'])->name('create')->middleware('can:ledger-group-create');
        Route::post('/store', [LedgerGroupController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [LedgerGroupController::class, 'edit'])->name('edit')->middleware('can:ledger-group-edit');
        Route::post('/update/{id}', [LedgerGroupController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [LedgerGroupController::class, 'destroy'])->name('delete')->middleware('can:ledger-group-delete');
        Route::get('/view/{id}', [LedgerGroupController::class, 'show'])->name('show')->middleware('can:ledger-group-view');
        Route::get('/import/format', [LedgerGroupController::class, 'downloadFormat'])->name('import.format');
        Route::post('/import', [LedgerGroupController::class, 'import'])->name('import');
    }); 

    /* ==================== ledger group category  =================== */
    Route::prefix('ledger-sub-group')->as('ledger.sub.group.')->group(function () {
        Route::get('/', [LedgerSubGroupController::class, 'index'])->name('index');
        Route::get('/create', [LedgerSubGroupController::class, 'create'])->name('create');
        Route::post('/store', [LedgerSubGroupController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [LedgerSubGroupController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [LedgerSubGroupController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [LedgerSubGroupController::class, 'destroy'])->name('destroy');
        Route::get('/view/{id}', [LedgerSubGroupController::class, 'show'])->name('show');
        Route::get('/import/format', [LedgerSubGroupController::class, 'downloadFormat'])->name('import.format');
        Route::post('/import', [LedgerSubGroupController::class, 'import'])->name('import');
    }); 

    /* ==================== journal voucher  =================== */
    Route::prefix('journal-voucher')->as('journal-voucher.')->group(function () {
        Route::get('/', [JournalController::class, 'index'])->name('index')->middleware('can:journal-list');
        Route::get('/excel', [JournalController::class, 'excel'])->name('excel');
        Route::get('/create', [JournalController::class, 'create'])->name('create')->middleware('can:journal-create');
        Route::get('/create-manual', [JournalController::class, 'manuallyCreate'])->name('manually.create');
        Route::get('/create-manual-capital', [JournalController::class, 'manuallyCapitalCreate'])->name('manually.capital.create');
        Route::post('/store', [JournalController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [JournalController::class, 'edit'])->name('edit')->middleware('can:journal-edit');
        Route::post('/update/{id}', [JournalController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [JournalController::class, 'destroy'])->name('delete')->middleware('can:journal-delete');
        Route::get('/view/{id}', [JournalController::class, 'show'])->name('show')->middleware('can:journal-view');
        Route::get('/get-branches/{companyId}', [JournalController::class, 'getBranchesByCompany']);
        Route::get('/import/format', [JournalController::class, 'downloadFormat'])->name('import.format');
        Route::post('/import', [JournalController::class, 'import'])->name('import');
        Route::post('/update-status', [JournalController::class, 'updateStatus'])->name('update-status');

    }); 

    /* ==================== Report =================== */
    Route::prefix('report/accounts')->as('report.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index')->middleware('can:report-list');
        Route::get('/trial/balance', [ReportController::class, 'trialBalance'])->name('trial.balance')->middleware('can:trial-balnce-report');
        Route::get('/balance/sheet', [ReportController::class, 'balanceSheet'])->name('balance.sheet')->middleware('can:balance-shit-report');
        Route::get('/ledger', [ReportController::class, 'ledgerList'])->name('ledger.report');
        Route::get('/ledger/report/{id}', [ReportController::class, 'ledgerReport'])->name('ledger.single.report');
        Route::get('/ledger/group', [ReportController::class, 'ledgerGroupList'])->name('ledger.group.report');
        Route::get('/ledger/group/report/{id}', [ReportController::class, 'ledgerGroupReport'])->name('ledger.group.single.report');
        Route::get('/ledger/pay-slip/{id}', [ReportController::class, 'getLedgerPaySlip'])->name('ledger.pay.slip');
        Route::get('/ledger/profit/loss', [ReportController::class, 'ledgerProfitLoss'])->name('ledger.profit.loss');
        Route::get('/project/profit/loss', [ReportController::class, 'ProjectProfitLoss'])->name('project.profit.loss');

    });

    /* ==================== Chart of account =================== */
    Route::prefix('chart_of_accounts')->as('chart_of_accounts.')->group(function () {
        Route::get('/', [ChartOfAccountController::class, 'index'])->name('index');
        Route::get('/create', [ChartOfAccountController::class, 'create'])->name('create');
    }); 

    /* ==================== Invoice =================== */
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'AdminInvoiceIndex'])->name('admin.invoice');
        
        Route::get('/create', [InvoiceController::class, 'AdminInvoiceCreate'])->name('admin.invoiceCreate');
    });

    /* ==================== Projects =================== */
    // Route::prefix('projects')->group(function () {
    //     Route::get('/', [ProjectController::class, 'AdminProjectIndex'])->name('admin.project');
    //     Route::get('/create', [ProjectController::class, 'AdminProjectCreate'])->name('admin.projectCreate');
    //     Route::get('/details/1', [InvoiceController::class, 'AdminInvoiceDetails'])->name('admin.invoiceDetails');
    //     Route::post('/store', [ProjectController::class, 'store'])->name('projects.store');
    //     Route::delete('/delete/{id}', [ProjectController::class, 'destroy'])->name('admin.projectDelete');
    //     Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('admin.projectEdit');
    //     Route::get('/view/{id}', [ProjectController::class, 'view'])->name('admin.projectView');
    //     Route::put('/update/{id}', [ProjectController::class, 'update'])->name('admin.projectUpdate');
    // });

    /* ==================== supplier =================== */
    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'AdminSupplierIndex'])->name('admin.supplier.index');
        Route::get('/create', [SupplierController::class, 'AdminSupplierCreate'])->name('admin.supplier.create');
        Route::post('/storeSupplier2', [SupplierController::class, 'AdminSupplierStore2'])->name('admin.supplier2.store');
        Route::post('/storeSupplier', [SupplierController::class, 'AdminSupplierStore'])->name('admin.supplier.store');
        Route::get('/view/{id}', [SupplierController::class, 'AdminSupplierView'])->name('admin.supplier.view');
        Route::get('/edit/{id}', [SupplierController::class, 'AdminSupplierEdit'])->name('admin.supplier.edit');
        Route::put('/update/{id}', [SupplierController::class, 'AdminSupplierUpdate'])->name('admin.supplier.update');
        Route::delete('/delete/{id}', [SupplierController::class, 'AdminSupplierDestroy'])->name('admin.supplier.destroy');
        Route::get('/products/{supplier}', [SupplierController::class, 'viewProducts'])->name('admin.supplier.products');
        Route::get('/transactions/{supplier}', [SupplierController::class, 'viewTransactions'])->name('admin.supplier.transactions');
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
        Route::get('/', [CompanyInformationController::class, 'index'])->name('index')->middleware('can:setting-information');
        Route::get('edit/{id}', [CompanyInformationController::class, 'edit'])->name('edit')->middleware('can:setting-information-edit');
        Route::post('update/{id}', [CompanyInformationController::class, 'update'])->name('update');
        Route::get('import', [CompanyInformationController::class, 'import'])->name('import');
        Route::get('export', [CompanyInformationController::class, 'export'])->name('export');

        Route::get('ledgerExport', [CompanyInformationController::class, 'ledgerExport'])->name('ledgerExport');
        Route::get('ledgerGroupExport', [CompanyInformationController::class, 'ledgerGroupExport'])->name('ledgerGroupExport');
        Route::get('journalExport', [CompanyInformationController::class, 'journalExport'])->name('journalExport');
    });

    /* ==================== Role and User Management =================== */
    Route::resource('roles', RoleController::class) ->middleware([
        'can:role-list',   
        'can:role-create',  
        'can:role-edit',   
        'can:role-delete',
        'can:role-view',
    ]);
    Route::get('roles/delete/{id}', [RoleController::class, 'destroy'])->name('roles.delete');
    Route::resource('users', UserController::class)->middleware([
        'can:user-list',   
        'can:user-create',  
        'can:user-edit',   
        'can:user-delete',   
        'can:user-view',   
    ]);
    Route::get('users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');

    /* ==================== Category =================== */
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'AdminCategoryIndex'])->name('admin.category.index');
        Route::get('/create', [CategoryController::class, 'AdminCategoryCreate'])->name('admin.category.create');
        Route::post('/storeCategory', [CategoryController::class, 'AdminCategoryStore'])->name('admin.category.store');
        Route::post('/storeCategory2', [CategoryController::class, 'AdminCategoryStore2'])->name('admin.category.store2');
        Route::get('/edit/{id}', [CategoryController::class, 'AdminCategoryEdit'])->name('admin.category.edit');
        Route::put('/update/{id}', [CategoryController::class, 'AdminCategoryUpdate'])->name('admin.category.update');
        Route::get('/delete/{id}', [CategoryController::class, 'AdminCategoryDestroy'])->name('admin.category.destroy');
    });

    /* ==================== Unit =================== */
    Route::prefix('unit')->group(function () {
        Route::get('/', [UnitController::class, 'AdminUnitIndex'])->name('admin.unit.index');
        Route::get('/create', [UnitController::class, 'AdminUnitCreate'])->name('admin.unit.create');
        Route::post('/storeUnit', [UnitController::class, 'AdminUnitStore'])->name('admin.unit.store');
        Route::post('/storeUnit2', [UnitController::class, 'AdminUnitStore2'])->name('admin.unit.store2');
        Route::get('/edit/{id}', [UnitController::class, 'AdminUnitEdit'])->name('admin.unit.edit');
        Route::put('/update/{id}', [UnitController::class, 'AdminUnitUpdate'])->name('admin.unit.update');
        Route::get('/delete/{id}', [UnitController::class, 'AdminUnitDestroy'])->name('admin.unit.destroy');
    });

    /* ==================== Product =================== */
    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'AdminProductIndex'])->name('admin.product.index');
        Route::get('/create', [ProductController::class, 'AdminProductCreate'])->name('admin.product.create');
        Route::post('/storeProduct', [ProductController::class, 'AdminProductStore'])->name('admin.product.store');
        Route::get('/edit/{id}', [ProductController::class, 'AdminProductEdit'])->name('admin.product.edit');
        Route::put('/update/{id}', [ProductController::class, 'AdminProductUpdate'])->name('admin.product.update');
        Route::get('/delete/{id}', [ProductController::class, 'AdminProductDestroy'])->name('admin.product.destroy');
        Route::get('/products-by-category/{categoryId}', [ProductController::class, 'getProductsByCategory'])->name('products.by.category');
    });

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
        Route::post('/store', [PurchaseController::class, 'AdminPurchaseStore'])->name('admin.purchase.store');
        Route::get('/view/{id}', [PurchaseController::class, 'AdminPurchaseView'])->name('admin.purchase.show');
        Route::get('/edit/{id}', [PurchaseController::class, 'AdminPurchaseEdit'])->name('admin.purchase.edit');
        Route::put('/update/{id}', [PurchaseController::class, 'AdminPurchaseUpdate'])->name('admin.purchase.update');
        Route::get('/delete/{id}', [PurchaseController::class, 'destroy'])->name('admin.purchase.destroy');
        Route::get('/get-invoice-details/{id}', [PurchaseController::class, 'getInvoiceDetails']);
        Route::get('/print', [PurchaseController::class, 'Print'])->name('admin.purchase.print');
    });
    
    /* ==================== Sales Payment Controller =================== */
    Route::prefix('payment/sales')->group(function () {
        Route::get('/', [SalePaymentController::class, 'index'])->name('sale.payment.index');
        Route::get('/create', [SalePaymentController::class, 'create'])->name('sale.payment.create');
        Route::post('/store', [SalePaymentController::class, 'store'])->name('sale.payment.store');
        Route::get('/view/{id}', [SalePaymentController::class, 'view'])->name('sale.payment.show');
        Route::get('/edit/{id}', [SalePaymentController::class, 'edit'])->name('sale.payment.edit');
        Route::put('/update/{id}', [SalePaymentController::class, 'update'])->name('sale.payment.update');
        Route::get('/delete/{id}', [SalePaymentController::class, 'destroy'])->name('sale.payment.destroy');
        Route::get('/get-ledgers-by-group', [SalePaymentController::class, 'getLedgersByGroup'])->name('sale.payment.get.ledgers.by.group');
        Route::get('/payment/get-chalans-by-supplier', [SalePaymentController::class, 'getChalansBySupplier'])->name('sale.payment.get.chalans.by.supplier');
        Route::get('/get-purchase-details', [SalePaymentController::class, 'getPurchaseDetails'])->name('sale.payment.get.purchase.details');

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
    
    /* ==================== Sales Receipt Controller =================== */
    Route::prefix('payment/receipt')->group(function () {
        Route::get('/', [SaleReceiptController::class, 'index'])->name('receipt.payment.index');
        Route::get('/create', [SaleReceiptController::class, 'create'])->name('receipt.payment.create');
        Route::post('/store', [SaleReceiptController::class, 'store'])->name('receipt.payment.store');
        Route::get('/view/{id}', [SaleReceiptController::class, 'view'])->name('receipt.payment.show');
        Route::get('/edit/{id}', [SaleReceiptController::class, 'edit'])->name('receipt.payment.edit');
        Route::put('/update/{id}', [SaleReceiptController::class, 'update'])->name('receipt.payment.update');
        Route::get('/delete/{id}', [SaleReceiptController::class, 'destroy'])->name('receipt.payment.destroy');
        Route::get('/get-ledgers-by-group', [SaleReceiptController::class, 'getLedgersByGroup'])->name('receipt.payment.get.ledgers.by.group');
        Route::get('/payment/get-chalans-by-client', [SaleReceiptController::class, 'getChalansByClient'])->name('receipt.payment.get.chalans.by.client');
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

    // Project Routes
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/delete/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/projects/sales/{id}', [ProjectController::class, 'projectsSales'])->name('projects.sales');
    Route::get('/project/details', [ProjectController::class, 'getProjectDetails'])->name('project.get.details');



    /* ==================== Project Payment Receipt Controller =================== */
    Route::prefix('project/payment/receipt')->group(function () {
        Route::get('/', [ProductSaleReceiveController::class, 'index'])->name('project.receipt.payment.index');
        Route::get('/create', [ProductSaleReceiveController::class, 'create'])->name('project.receipt.payment.create');
        Route::post('/store', [ProductSaleReceiveController::class, 'store'])->name('project.receipt.payment.store');
        // Route::get('/view/{invoice_no}', [ProductSaleReceiveController::class, 'view'])->name('project.receipt.payment.show');
        Route::get('/view', [ProductSaleReceiveController::class, 'view'])->name('project.receipt.payment.show');
        Route::get('/edit/{id}', [ProductSaleReceiveController::class, 'edit'])->name('project.receipt.payment.edit');
        Route::put('/update/{id}', [ProductSaleReceiveController::class, 'update'])->name('project.receipt.payment.update');
        Route::get('/delete/{id}', [ProductSaleReceiveController::class, 'destroy'])->name('project.receipt.payment.destroy');
        Route::get('/get-ledgers-by-group', [ProductSaleReceiveController::class, 'getLedgersByGroup'])->name('project.receipt.payment.get.ledgers.by.group');
        Route::get('/payment/get-chalans-by-client', [ProductSaleReceiveController::class, 'getChalansByClient'])->name('project.receipt.payment.get.chalans.by.client');
    });
    
    // Quotation Routes
    Route::resource('quotations', QuotationController::class);
    Route::get('/quotations/delete/{id}', [QuotationController::class, 'destroy'])->name('quotations.destroy');
    // Work Order Routes
    Route::resource('workorders', WorkOrderController::class);
    Route::get('/delete/{id}', [WorkOrderController::class, 'destroy'])->name('workorders.destroy');
    Route::get('/workorders/invoice/{id}', [WorkOrderController::class, 'invoice'])->name('workorders.invoice');


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



/* =============== End Admin Route  ============= */

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
require __DIR__.'/zim.php';

Route::view('/invoice', 'backend.admin.invoice.invoice');
Route::view('/invoice2', 'backend.admin.invoice.invoice2');

Route::get('/get-sub-groups/{group_id}', function ($group_id) {
    Log::info("Fetching sub-groups for group ID: " . $group_id);
    $subGroups = LedgerSubGroup::where('ledger_group_id', $group_id)->pluck('subgroup_name', 'id');
    Log::info("Sub-groups fetched: ", $subGroups->toArray());
    return response()->json($subGroups);
});

Route::get('/purchase-details/{purchase_id}', [ProjectController::class, 'showDetails']);
Route::get('/admin/purchase/view', [PurchaseController::class, 'AdminPurchaseView2'])->name('admin.purchase.view');
