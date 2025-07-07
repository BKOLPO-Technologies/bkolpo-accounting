@php
  // Determine active state for menu items
  $isReportActive = Route::is('report.index','report.trial.balance','report.balance.sheet','report.ledger.report','report.ledger.single.report','report.ledger.group.report','report.ledger.group.single.report','report.ledger.profit.loss','report.project.profit.loss','report.daybook','report.sales','report.purchases','report.purchases.sales','report.bills.payable','report.bills.receivable');
  $isSupplierActive = Route::is('admin.supplier.index','admin.supplier.create','admin.supplier.view','admin.supplier.edit','admin.supplier.products','admin.supplier.transactions');
  $isClientActive = Route::is('admin.client.index','admin.client.create','admin.client.view','admin.client.edit','admin.client.products','admin.client.transactions');
  $isProjectActive = Route::is('projects.index', 'projects.create', 'projects.show', 'projects.edit', 'projects.sales');
  $isInvoiceActive = Route::is('project.receipt.payment.show');
  $isSalesActive = Route::is('quotations.index','quotations.create','quotations.edit','quotations.show','outcoming.chalan.index','outcoming.chalan.create','outcoming.chalan.show','outcoming.chalan.edit','receipt.payment.index','receipt.payment.create','stock.out','stock.out.view');
  $isPurchaseActive = Route::is('workorders.index','workorders.create','workorders.edit','workorders.show','incoming.chalan.index','incoming.chalan.create','incoming.chalan.show','incoming.chalan.edit');
  $isAccountMasterActive = Route::is('journal-voucher.*', 'chart_of_accounts.*', 'ledger.*', 'ledger.group.*', 'ledger.sub.group.*', 'admin.client.index','admin.client.create','admin.client.view','admin.client.edit','admin.client.products','admin.client.transactions', 'admin.supplier.index','admin.supplier.create','admin.supplier.view','admin.supplier.edit','admin.supplier.products','admin.supplier.transactions');
  // new
  $isTransactionsActive = Route::is('admin.purchase.invoice.index','admin.purchase.invoice.create','admin.purchase.invoice.show','admin.purchase.invoice.edit','admin.purchase.order.index','admin.purchase.order.create','admin.purchase.order.edit','admin.purchase.order.create','admin.sale.index','admin.sale.create','admin.sale.show','admin.sale.edit','workorders.index','workorders.create','workorders.edit','workorders.show','incoming.chalan.index','incoming.chalan.create','incoming.chalan.show','incoming.chalan.edit','sale.payment.index','sale.payment.create','stock.in','stock.in.view', 'sale.payment.show','project.receipt.payment.index', 'project.receipt.payment.create', 'project.receipt.payment.show','contra-voucher.create','contra-voucher.index','contra-voucher.edit');
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('admin.dashboard') }}" class="brand-link">
    {{-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
    <span class="brand-text fw-light">ERP <strong>Management System</strong></span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('backend/assets/img/AdminLTELogo.png') }}" class="img-circle elevation-2" alt="User Image">
      </div>

      <div class="info">
        <a href="{{ route('admin.dashboard') }}" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @can('dashboard-menu')  
        <li class="nav-item menu-open">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        @endcan

        <!-- Clients -->
        {{-- <li class="nav-item {{ $isClientActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isClientActive ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
              Clients
                  <i class="fas fa-angle-left right"></i>
              </p>
          </a>
        
          <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.client.index') }}" class="nav-link {{ Route::is('admin.client.index') || Route::is('admin.client.create') || Route::is('admin.client.view') || Route::is('admin.client.edit') || Route::is('admin.client.products')  || Route::is('admin.client.transactions') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Manage Clients</p>
                </a>
            </li>
          </ul>
        </li> --}}

        <!-- Supplier -->
        {{-- <li class="nav-item {{ $isSupplierActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isSupplierActive ? 'active' : '' }}">
              <i class="nav-icon fas fa-truck"></i>
              <p>
                Vendors
                  <i class="fas fa-angle-left right"></i>
              </p>
          </a>
      
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.supplier.index') }}" class="nav-link {{ Route::is('admin.supplier.index') || Route::is('admin.supplier.view') || Route::is('admin.supplier.edit') || Route::is('admin.supplier.create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Vendors</p>
              </a>
            </li>
          </ul>
        </li> --}}

        <!-- =================== Start Accounts Main Menu =================== -->
        <li class="nav-item {{ $isAccountMasterActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isAccountMasterActive ? 'active' : '' }}">
            <i class="nav-icon fas fa-university"></i>
            <p>
              Account Master
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <!-- === Journal Submenu === -->
          @can('journal-menu')  
          <ul class="nav nav-treeview shadow-lg">
            <li class="nav-item {{ Route::is('journal-voucher.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ Route::is('journal-voucher.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book"></i>
                <p>
                  Journal
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('journal-list')  
                <li class="nav-item">
                  <a href="{{ route('journal-voucher.index') }}" class="nav-link {{ Route::is('journal-voucher.index', 'journal-voucher.create', 'journal-voucher.manually.capital.create') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Journal List</p>
                  </a>
                </li>
                @endcan
                {{-- <li class="nav-item">
                  <a href="{{ route('journal-voucher.excel') }}" class="nav-link {{ Route::is('journal-voucher.excel') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Journal Excel Entry List</p>
                  </a>
                </li> --}}
              </ul>
            </li>
          </ul>
          @endcan
          <!-- === End Journal Submenu === -->

          <!-- === Chart of Accounts Submenu === -->
          @can('ledger-menu')  
          <ul class="nav nav-treeview shadow-lg">
            <li class="nav-item {{ Route::is('ledger.*', 'ledger.group.*', 'ledger.sub.group.*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ Route::is('ledger.*', 'ledger.group.*', 'ledger.sub.group.*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>
                  Chart of Accounts
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('ledger-group-menu')  
                <li class="nav-item">
                  <a href="{{ route('ledger.group.index') }}" class="nav-link {{ Route::is('ledger.group.*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Group List</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('ledger.sub.group.index') }}" class="nav-link {{ Route::is('ledger.sub.group.*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Sub Group List</p>
                  </a>
                </li>
                @endcan
                @can('ledger-menu')  
                <li class="nav-item">
                  <a href="{{ route('ledger.index') }}" class="nav-link {{ Route::is('ledger.index', 'ledger.create', 'ledger.edit', 'ledger.show') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ledger List</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
          </ul>
          @endcan
          <!-- === End Chart of Accounts Submenu === -->

          <!-- === Customers === -->
          <ul class="nav nav-treeview shadow-lg">
            <li class="nav-item {{ $isClientActive ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ $isClientActive ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
                  <p>
                    Customers
                    <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
            
              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.client.index') }}" class="nav-link {{ Route::is('admin.client.index') || Route::is('admin.client.create') || Route::is('admin.client.view') || Route::is('admin.client.edit') || Route::is('admin.client.products')  || Route::is('admin.client.transactions') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Customers</p>
                    </a>
                </li>
              </ul>
            </li>
          </ul>
          <!-- === End of Customers === -->

          <!-- === Vendors === -->
          <ul class="nav nav-treeview shadow-lg">
            <li class="nav-item {{ $isSupplierActive ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ $isSupplierActive ? 'active' : '' }}">
                  <i class="nav-icon fas fa-truck"></i>
                  <p>
                    Vendors
                    <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
          
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('admin.supplier.index') }}" class="nav-link {{ Route::is('admin.supplier.index') || Route::is('admin.supplier.view') || Route::is('admin.supplier.edit') || Route::is('admin.supplier.create') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Manage Vendors</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
          <!-- === End of Vendors === -->

        </li>
        <!-- =================== End Accounts Main Menu =================== -->

        <!-- Projects -->
        <li class="nav-item {{ $isProjectActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isProjectActive ? 'active' : '' }}">
              <i class="nav-icon fas fa-folder"></i> <!-- Updated Icon -->
              <p>
                  Projects
                  <i class="fas fa-angle-left right"></i>
              </p>
          </a>
          
          <ul class="nav nav-treeview">
              <li class="nav-item">
                  <a href="{{ route('projects.index') }}" class="nav-link {{ Route::is('projects.index', 'projects.create', 'projects.show', 'projects.edit', 'projects.sales') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Manage Projects</p>
                  </a>
              </li>
          </ul>
        </li>
        
        <!-- Sales -->
        {{-- <li class="nav-item {{ $isSalesActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isSalesActive ? 'active' : '' }}">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
              Sales
                  <i class="fas fa-angle-left right"></i>
              </p>
          </a>
          
          <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.sale.index') }}" class="nav-link {{ Route::is('admin.sale.index','admin.sale.create','admin.sale.show','admin.sale.edit') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Order Invoice List</p>
                </a>
            </li>
          </ul>
        </li> --}}

        <!-- Transactions -->
        <li class="nav-item {{ $isTransactionsActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isTransactionsActive ? 'active' : '' }}">
            <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                  Transactions
                  <i class="fas fa-angle-left right"></i>
              </p>
          </a>
          
          <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('project.receipt.payment.index') }}" class="nav-link {{ Route::is('project.receipt.payment.index','project.receipt.payment.create') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Receipt</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('sale.payment.index') }}" class="nav-link {{ Route::is('sale.payment.index','sale.payment.create') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Payment</p>
                </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('journal-voucher.index') }}" class="nav-link {{ Route::is('journal-voucher.index', 'journal-voucher.create', 'journal-voucher.manually.capital.create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Journal</p>
              </a>
            <li class="nav-item">
              <a href="{{ route('contra-voucher.index') }}" class="nav-link {{ Route::is('contra-voucher.index','contra-voucher.create','contra-voucher.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Contra</p>
              </a>
            </li>
            </li>
            <li class="nav-item">
              <a href="#"  onclick="comingSoon()" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Journal</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.purchase.order.index') }}" class="nav-link {{ Route::is('admin.purchase.order.index','admin.purchase.order.create', 'admin.purchase.order.show', 'admin.purchase.order.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Order</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.purchase.invoice.index') }}" class="nav-link {{ Route::is('admin.purchase.invoice.index','admin.purchase.invoice.create', 'admin.purchase.invoice.show', 'admin.purchase.invoice.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Invoice</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.sale.index') }}" class="nav-link {{ Route::is('admin.sale.index','admin.sale.create','admin.sale.show','admin.sale.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Invoice</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Sales -->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>
              Sales
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <!-- Proforma Invoice -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Proforma Invoice</p>
              </a>
            </li>

            <!-- Sales Order -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Order</p>
              </a>
            </li>

            <!-- Delivery Note -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Delivery Note</p>
              </a>
            </li>

            <!-- Invoice/Bill -->
            <li class="nav-item">
              <a href="{{ route('admin.sale.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Invoice/Bill</p>
              </a>
            </li>

            <!-- Sales Return -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Return</p>
              </a>
            </li>

            <!-- Warranty In -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Warranty In</p>
              </a>
            </li>

            <!-- Customers -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Customers</p>
              </a>
            </li>

            <!-- Salesman Performance -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Salesman Performance</p>
              </a>
            </li>

            <!-- Salesman-wise Receivable -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Salesman Receivable</p>
              </a>
            </li>

            <!-- Sales Import -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Import</p>
              </a>
            </li>

            <!-- Team -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Team</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Purchase -->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-bag"></i>
            <p>
              Purchase
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <!-- Purchase Requisition -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Requisition</p>
              </a>
            </li>

            <!-- Purchase Quotation -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Quotation</p>
              </a>
            </li>

            <!-- Purchase Order -->
            <li class="nav-item">
              <a href="{{ route('admin.purchase.order.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Order</p>
              </a>
            </li>

            <!-- Receipt Note -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Receipt Note</p>
              </a>
            </li>

            <!-- Purchase Invoice -->
            <li class="nav-item">
              <a href="{{ route('admin.purchase.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Invoice</p>
              </a>
            </li>

            <!-- Purchase Return -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Return</p>
              </a>
            </li>

            <!-- Purchase Import -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Purchase Import</p>
              </a>
            </li>

            <!-- Vendors -->
            <li class="nav-item">
              <a href="#" onclick="comingSoon()" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Vendors</p>
              </a>
            </li>
          </ul>
        </li>



        <!-- Report Menu -->
        @can('report-menu')  
        <li class="nav-item {{ $isReportActive ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $isReportActive ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>
                Accounting Report
                <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <!-- Accounts Submenu -->
            @can('report-menu')  
            <li class="nav-item">
              <a href="{{ route('report.trial.balance') }}" class="nav-link {{ Route::is('report.trial.balance') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Trial Balance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('report.balance.sheet') }}" class="nav-link {{ Route::is('report.balance.sheet') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Balance Sheet</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('report.ledger.profit.loss') }}" class="nav-link {{ Route::is('report.ledger.profit.loss') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Profit and Loss</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#"  onclick="comingSoon()" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Receipts & Payments</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('report.daybook') }}" class="nav-link {{ Route::is('report.daybook') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daybook</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#"  onclick="comingSoon()" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Statement</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#"  onclick="comingSoon()" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Groupwise Statement</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('report.bills.receivable') }}" class="nav-link {{ Route::is('report.bills.receivable') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bills Receivable</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('report.bills.payable') }}" class="nav-link {{ Route::is('report.bills.payable') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bills Payable</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('report.purchases.sales') }}" class="nav-link {{ Route::is('report.purchases.sales') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase And Sales</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('report.sales') }}" class="nav-link {{ Route::is('report.sales') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Report</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('report.purchases') }}" class="nav-link {{ Route::is('report.purchases') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Purchase Report</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcan

        <!-- ---Company--- -->
        @can('company-menu')  
        <li class="nav-item {{ Route::is('company.index','company.create','company.edit','company.show','users.index','users.create','users.edit','users.show','roles.index','roles.create','roles.edit','roles.show') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('company.index','company.create','company.edit','company.show','users.index','users.create','users.edit','users.show','roles.index','roles.create','roles.edit','roles.show') ? 'active' : '' }}">
            <i class="nav-icon fas fa-building"></i>
            <p>
              Company
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
          @can('company-list') 
            <li class="nav-item">
              <a href="{{ route('company.index') }}" class="nav-link {{ Route::is('company.index','company.create','company.edit','company.show') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Company List</p>
              </a>
            </li>
            @endcan
            @can('user-menu')  
              <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link {{ Route::is('users.index','users.create','users.edit','users.show') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>User List</p>
                </a>
              </li>
              @endcan
              @can('role-list')
              <li class="nav-item">
                <a href="{{ route('roles.index') }}" class="nav-link {{ Route::is('roles.index','roles.create','roles.edit','roles.show') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Role List</p>
                </a>
              </li>
            @endcan
          </ul>
        </li>
        @endcan
        <!-- End---Company -->

        <!-- ---Branch--- -->
        @can('branch-menu')  
        <li class="nav-item {{ Route::is('branch.index', 'branch.create', 'branch.edit','branch.show') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('branch.index', 'branch.create', 'branch.edit','branch.show') ? 'active' : '' }}">
            <i class="nav-icon fas fa-project-diagram"></i>
            <p>
              Branch
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
          @can('branch-list')  
            <li class="nav-item">
              <a href="{{ route('branch.index') }}" class="nav-link {{ Route::is('branch.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Branch List</p>
              </a>
            </li>
            @endcan
          </ul>
        </li>
        @endcan
        <!-- End---Branch -->

        <!-- Product -->
        <li class="nav-item {{ Route::is('admin.product*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('admin.product*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-box"></i>
            <p>
              Product
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          {{-- 
          <ul class="nav nav-treeview">
              <li class="nav-item">
                  <a href="{{ route('admin.category.index') }}" class="nav-link {{ Route::is('admin.category.index') || Route::is('admin.category.create') || Route::is('admin.category.edit') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Manage Category</p>
                  </a>
              </li>
          </ul> 
          --}}

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.product.index') }}" class="nav-link {{ Route::is('admin.product.index') || Route::is('admin.product.create') || Route::is('admin.product.edit') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Manage Product</p>
              </a>
            </li>
          </ul>
        </li>

        @can('setting-menu')  
        <li class="nav-item {{ Route::is('company-information.index','company-information.import','company-information.export', 'admin.category*', 'admin.unit*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('company-information.index','company-information.import','company-information.export', 'admin.category*', 'admin.unit*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
                <i class="fas fa-angle-left right"></i>
              </p>
          </a>
          <ul class="nav nav-treeview">
            {{-- @can('setting-information')  
            <li class="nav-item">
              <a href="{{ route('company-information.index') }}" class="nav-link {{ Route::is('company-information.index') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Configuration</p> 
              </a>
            </li>
            @endcan --}}

            <li class="nav-item">
              <a href="{{ route('admin.category.index') }}" class="nav-link {{ Route::is('admin.category.index') || Route::is('admin.category.create') || Route::is('admin.category.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Products Category</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.unit.index') }}" class="nav-link {{ Route::is('admin.unit.index') || Route::is('admin.unit.create') || Route::is('admin.unit.edit') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Products Unit</p> 
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('company-information.import') }}" class="nav-link {{ Route::is('company-information.import') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Import</p> 
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('company-information.export') }}" class="nav-link {{ Route::is('company-information.export') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Export</p> 
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('company-information.index') }}" class="nav-link {{ Route::is('company-information.index') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Configuration</p> 
              </a>
            </li>
          </ul>
        </li>
        @endcan

        <!-- ---Bank--- -->
        <!-- <li class="nav-item {{ Route::is('bank.index', 'bank.create', 'bank.edit','bank.show') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('bank.index', 'bank.create', 'bank.edit','bank.show') ? 'active' : '' }}">
            <i class="nav-icon fas fa-university"></i>
            <p>
              Bank
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('bank.index') }}" class="nav-link {{ Route::is('bank.index', 'bank.create', 'bank.edit','bank.show') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Bank List</p>
              </a>
            </li>
          </ul>
        </li> -->
        <!-- ---------------- -->

        <!-- --- Payment Method --- -->
        <!-- <li class="nav-item {{ Route::is('payment.index', 'payment.create', 'payment.edit','payment.show') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('payment.index', 'payment.create', 'payment.edit','payment.show') ? 'active' : '' }}">
            <i class="nav-icon fas fa-university"></i>
            <p>
              Payment Method
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('payment.index') }}" class="nav-link {{ Route::is('payment.index', 'payment.create', 'payment.edit','payment.show') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Payment Method List</p>
              </a>
            </li>
          </ul>
        </li> -->
        <!-- ---------------- -->

        <!-- Category -->
        {{-- <li class="nav-item {{ Route::is('admin.category*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ Route::is('admin.category*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                  Category
                  <i class="fas fa-angle-left right"></i>
              </p>
          </a>

          <ul class="nav nav-treeview">
              <li class="nav-item">
                  <a href="{{ route('admin.category.index') }}" class="nav-link {{ Route::is('admin.category.index') || Route::is('admin.category.create') || Route::is('admin.category.edit') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Manage Category</p>
                  </a>
              </li>
          </ul>
        </li> --}}

        {{-- <li class="nav-item {{ Route::is('admin.category*') || Route::is('admin.product*') || Route::is('stock*') ? 'menu-open' : '' }}"> --}}
            {{--               
            <a href="#" class="nav-link {{ Route::is('admin.category*') || Route::is('admin.product*') || Route::is('stock*')  ? 'active' : '' }}">
                <i class="nav-icon fas fa-boxes"></i>
                <p>
                  Inventory
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
              --}}
            <!-- Category -->
            {{-- 
            <ul class="nav nav-treeview">
              <li class="nav-item {{ Route::is('admin.category*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.category*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                        Category
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.category.index') }}" class="nav-link {{ Route::is('admin.category.index') || Route::is('admin.category.create') || Route::is('admin.category.edit') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Manage Category</p>
                        </a>
                    </li>
                </ul>
              </li>
            </ul> 
            --}}

            <!-- Product -->
            {{-- 
            <ul class="nav nav-treeview">
                <li class="nav-item {{ Route::is('admin.product*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::is('admin.product*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            Product
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.product.index') }}" class="nav-link {{ Route::is('admin.product.index') || Route::is('admin.product.create') || Route::is('admin.product.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Product</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul> 
            --}}

            <!-- Stock Management -->
            {{-- 
            <ul class="nav nav-treeview">
              <li class="nav-item {{ Route::is('stock*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('stock*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Stock
                      <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('stock.index') }}" class="nav-link {{ Route::is('stock.index', 'stock.show') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Manage Stock</p>
                      </a>
                  </li>
                </ul>
              </li>
            </ul>
              --}}
        {{-- </li> --}}

        {{-- <li class="nav-item {{ Route::is('stock.index', 'stock.show') ? 'menu-open' : '' }}">
                          
            <a href="#" class="nav-link {{ Route::is('stock.index', 'stock.show')  ? 'active' : '' }}">
                <i class="nav-icon fas fa-boxes"></i>
                <p>
                  Inventory
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>

            <!-- Stock Management -->
            <ul class="nav nav-treeview">
              <li class="nav-item {{ Route::is('stock.index', 'stock.show') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('stock.index', 'stock.show') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Stock
                      <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('stock.index') }}" class="nav-link {{ Route::is('stock.index', 'stock.show') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Manage Stock</p>
                      </a>
                  </li>
                </ul>
              </li>
            </ul>
            
        </li> --}}

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>