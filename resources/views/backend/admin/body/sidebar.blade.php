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
          <!-- --- -->

          <!-- ---Company--- -->
          @can('company-menu')  
         <li class="nav-item {{ Route::is('company.index','company.create','company.edit','company.show') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('company.index','company.create','company.edit','company.show') ? 'active' : '' }}">
              <i class="nav-icon fas fa-project-diagram"></i>
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
            </ul>
          </li>
          @endcan
          <!-- --- -->

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

          <!-- --- Ledger Category --- -->
          @can('ledger-menu')  
          <li class="nav-item {{ Route::is('ledger.index', 'ledger.create', 'ledger.edit','ledger.show','ledger.group.index', 'ledger.group.create', 'ledger.group.edit','ledger.group.show','ledger.group.import') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('ledger.index', 'ledger.create', 'ledger.edit','ledger.show','ledger.group.index', 'ledger.group.create', 'ledger.group.edit','ledger.group.show','ledger.group.import') ? 'active' : '' }}">
              <i class="nav-icon fas fa-university"></i>
              <p>
                Chart Of Accounts 
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              @can('ledger-group-menu')  
                <a href="{{ route('ledger.group.index') }}" class="nav-link {{ Route::is('ledger.group.index', 'ledger.group.create', 'ledger.group.edit','ledger.group.show','ledger.group.import') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Group List</p>
                </a>
                @endcan
                @can('ledger-menu')  
                <a href="{{ route('ledger.index') }}" class="nav-link {{ Route::is('ledger.index', 'ledger.create', 'ledger.edit','ledger.show','ledger.group.import') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ledger List</p>
                </a>
                @endcan
              </li>
            </ul>
          </li>
          @endcan
          <!-- ---------------- -->

          <!----- Start Voucher Area ----->
          @can('journal-menu')  
          <li class="nav-item {{ Route::is('journal-voucher.index','journal-voucher.excel', 'journal-voucher.create', 'journal-voucher.edit', 'journal-voucher.show', 'chart_of_accounts.index', 'chart_of_accounts.create') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('journal-voucher.index','journal-voucher.excel', 'journal-voucher.create', 'journal-voucher.edit', 'journal-voucher.show', 'chart_of_accounts.index', 'chart_of_accounts.create') ? 'active' : '' }}">
              <i class="nav-icon fas fa-receipt"></i>
              <p>
              Journal
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            @can('journal-list')  
              <li class="nav-item">
                <a href="{{ route('journal-voucher.index') }}" class="nav-link {{ Route::is('journal-voucher.index','journal-voucher.create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Journal List</p>
                </a>
              </li>
              @endcan
              <li class="nav-item">
                <a href="{{ route('journal-voucher.excel') }}" class="nav-link {{ Route::is('journal-voucher.excel') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Journal Excel Entry List</p>
                </a>
              </li>
            </ul>
            <!-- <ul class="nav nav-treeview" style="{{ Route::is('chart_of_accounts.index', 'chart_of_accounts.create') ? 'display: block;' : 'display: none;' }}">
              <li class="nav-item {{ Route::is('chart_of_accounts.index', 'chart_of_accounts.create') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('chart_of_accounts.index', 'chart_of_accounts.create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Chart of account
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>

                <ul class="nav nav-treeview" style="{{ Route::is('chart_of_accounts.index', 'chart_of_accounts.create') ? 'display: block;' : 'display: none;' }}">
                  <li class="nav-item">
                    <a href="{{ route('chart_of_accounts.index') }}" class="nav-link {{ Route::is('chart_of_accounts.index') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Chart of account</p>
                    </a>
                  </li>
                </ul>

              </li>
            </ul> -->

          </li>
          @endcan
          <!----- End Voucher Area ----->

          @php
              // Determine active state for menu items
              $isReportActive = Route::is('report.index','report.trial.balance','report.balance.sheet','report.ledger.report','report.ledger.single.report','report.ledger.group.report','report.ledger.group.single.report','report.ledger.profit.loss');
          @endphp

          @can('report-menu')  
          <li class="nav-item {{ $isReportActive ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ $isReportActive ? 'active' : '' }}">
                  <i class="nav-icon fas fa-receipt"></i>
                  <p>
                      Report
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <!-- Accounts Submenu -->
                  @can('report-menu')  
                  <li class="nav-item {{ $isReportActive ? 'menu-open' : '' }}">
                      <a href="#" class="nav-link {{ $isReportActive ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>
                              Accounts
                              <i class="fas fa-angle-left right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          @can('report-list')  
                          <li class="nav-item">
                              <a href="{{ route('report.index') }}" class="nav-link {{ Route::is('report.index') ? 'active' : '' }}">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Report</p>
                              </a>
                          </li>
                          @endcan

                          @can('trial-balnce-report')  
                          <li class="nav-item">
                              <a href="{{ route('report.trial.balance') }}" class="nav-link {{ Route::is('report.trial.balance') ? 'active' : '' }}">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Trial Balance</p>
                              </a>
                          </li>
                          @endcan

                          @can('balance-shit-report')  
                          <li class="nav-item">
                              <a href="{{ route('report.balance.sheet') }}" class="nav-link {{ Route::is('report.balance.sheet') ? 'active' : '' }}">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Balance Sheet</p>
                              </a>
                          </li>
                          @endcan

                          <!-- <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Cost Of Revenue</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Profit Or Loss Accounts</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Retained Earnings</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Fixed Asset Schedule</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Cash Flow</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Receive Payment</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Notes</p>
                              </a>
                          </li> -->
                      </ul>
                  </li>
                  @endcan

                  <!-- General Submenu -->
                  <!-- <li class="nav-item">
                      <a href="#" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>
                              General
                              <i class="fas fa-angle-left right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Branch</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Ledger</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Bank Cash</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Voucher</p>
                              </a>
                          </li>
                      </ul>
                  </li> -->
              </ul>
          </li>
          @endcan


          <!-- ---Employee--- -->
          <!-- <li class="nav-item {{ Route::is('admin.employee.index', 'admin.employee.add') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('admin.employee.index', 'admin.employee.add') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Employee
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.employee.index') }}" class="nav-link {{ Route::is('admin.employee.index') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employee List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.employee.add') }}" class="nav-link {{ Route::is('admin.employee.add') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Employee</p>
                </a>
              </li>
            </ul>
          </li> -->
          <!-- ---Invoice--- -->
          <!-- <li class="nav-item {{ Route::is('admin.invoice*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('admin.invoice*') ? 'active' : '' }}">
              <i class="nav-icon fa-solid fa-file-invoice"></i>
              <p>
                Invoice
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.invoiceCreate') }}" class="nav-link {{ Route::is('admin.invoiceCreate') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>New Invoice</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.invoice') }}" class="nav-link {{ Route::is('admin.invoice') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Invoice</p>
                </a>
              </li>
            </ul>
          </li> -->
          <!-- ---------------- -->

          <!-- ---Customer--- -->
          <!-- <li class="nav-item {{ Route::is('admin.customer.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('admin.customer.*') ? 'active' : '' }}">
              <i class="nav-icon fa-solid fa-users"></i>
              <p>
                Customers
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.customer.create') }}" class="nav-link {{ Route::is('admin.customer.create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>New Customer</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.customer.index') }}" class="nav-link {{ Route::is('admin.customer.index') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Customers</p>
                </a>
              </li>
            </ul>
          </li> -->
          <!-- ---------------- -->


          <!-- ---Project--- -->
          <!-- <li class="nav-item {{ Route::is('admin.project*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('admin.project*') ? 'active' : '' }}">
              <i class="fa-solid fa-sheet-plastic"></i>
              <p>
                Project
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.projectCreate') }}" class="nav-link {{ Route::is('admin.projectCreate') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>New Project</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.project') }}" class="nav-link {{ Route::is('admin.project') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Project</p>
                </a>
              </li>
            </ul>
          </li> -->
          <!-- ---------------- -->

          <!-- ---Suppliers--- -->
          <!-- <li class="nav-item {{ Route::is('admin.supplier*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('admin.supplier*') ? 'active' : '' }}">
                <i class="fa-solid fa-money-check"></i>
                <p>
                    Suppliers
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.supplier.create') }}" class="nav-link {{ Route::is('admin.supplier.create') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>New Supplier</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.supplier.index') }}" class="nav-link {{ Route::is('admin.supplier.index') || Route::is('admin.supplier.view') || Route::is('admin.supplier.edit') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Suppliers</p>
                    </a>
                </li>
            </ul>
          </li> -->
          <!-- ---------------- -->


          <li class="nav-item {{ Route::is('admin.category*') || Route::is('admin.product*') || Route::is('admin.supplier*') || Route::is('admin.client*') || Route::is('admin.purchase*') || Route::is('admin.sale*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ Route::is('admin.category*') || Route::is('admin.product*') || Route::is('admin.supplier*') || Route::is('admin.client*') || Route::is('admin.purchase*') || Route::is('admin.sale*') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-receipt"></i>
                  <p>
                    Inventory
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>

              <!-- Supplier -->
              <ul class="nav nav-treeview">
                <li class="nav-item {{ Route::is('admin.supplier*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ Route::is('admin.supplier*') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                          Suppliers
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <!-- <li class="nav-item">
                          <a href="{{ route('admin.supplier.create') }}" class="nav-link {{ Route::is('admin.supplier.create') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>New Supplier</p>
                          </a>
                      </li> -->
                      <li class="nav-item">
                          <a href="{{ route('admin.supplier.index') }}" class="nav-link {{ Route::is('admin.supplier.index') || Route::is('admin.supplier.view') || Route::is('admin.supplier.edit') || Route::is('admin.supplier.create') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Suppliers</p>
                          </a>
                      </li>
                  </ul>
                </li>
              </ul>

              <!-- Client -->
              <ul class="nav nav-treeview">
                <li class="nav-item {{ Route::is('admin.client*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ Route::is('admin.client*') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                          Client
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('admin.client.index') }}" class="nav-link {{ Route::is('admin.client.index') || Route::is('admin.client.create') || Route::is('admin.client.view') || Route::is('admin.client.edit') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Clients</p>
                          </a>
                      </li>
                  </ul>
                </li>
              </ul>

              <!-- Category -->
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

              <!-- Product -->
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

              <!-- Purchase -->
              <ul class="nav nav-treeview">
                <li class="nav-item {{ Route::is('admin.purchase*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ Route::is('admin.purchase*') ? 'active' : '' }}">
                      <!-- <i class="fa-solid fa-money-check"></i> -->
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                          Purchase
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">

                      <li class="nav-item">
                          <a href="{{ route('admin.purchase.index') }}" class="nav-link {{ Route::is('admin.purchase.index','admin.purchase.create', 'admin.purchase.show', 'admin.purchase.edit') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Purchase List</p>
                          </a>
                      </li>
                  </ul>
                </li>
              </ul>

              <!-- Sales -->
              <ul class="nav nav-treeview">
                <li class="nav-item {{ Route::is('admin.sale*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ Route::is('admin.sale*') ? 'active' : '' }}">
                      <!-- <i class="fa-solid fa-money-check"></i> -->
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                          Sales
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">

                      <li class="nav-item">
                          <a href="{{ route('admin.sale.index') }}" class="nav-link {{ Route::is('admin.sale.index','admin.sale.create','admin.sale.show','admin.sale.edit') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Sales List</p>
                          </a>
                      </li>
                  </ul>
                </li>
              </ul>

              <!-- Stock Management -->
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="#" class="nav-link">
                      <!-- <i class="fa-solid fa-money-check"></i> -->
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                        Stock Management
                        <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                </li>
              </ul>

          </li>

          <!-- ---Transaction--- -->
          <!-- <li class="nav-item {{ Route::is('admin.transaction*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ Route::is('admin.transaction*') ? 'active' : '' }}">
                  <i class="fa-solid fa-tent-arrow-left-right"></i>
                  <p>
                      Transactions
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('admin.transaction.index') }}" class="nav-link {{ Route::is('admin.transaction.index') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>View Transactions</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('admin.transaction.add') }}" class="nav-link {{ Route::is('admin.transaction.add') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>New Transactions</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('admin.transaction.transfer') }}" class="nav-link {{ Route::is('admin.transaction.transfer') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>New Transfer</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('admin.transaction.income') }}" class="nav-link {{ Route::is('admin.transaction.income') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Income</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ route('admin.transaction.expense') }}" class="nav-link {{ Route::is('admin.transaction.expense') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Expense</p>
                      </a>
                  </li>
              </ul>
          </li> -->
          <!-- ---------------- -->
         <!-- <li class="nav-item">
           <a href="#" class="nav-link">
             <i class="nav-icon fas fa-language"></i>
             <p>
               Language
               <i class="fas fa-angle-left right"></i>
             </p>
           </a>
           <ul class="nav nav-treeview">
             <li class="nav-item">
               <a href="#" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Language List</p>
               </a>
             </li>
           </ul>
         </li> -->
         @can('user-menu')  
         <li class="nav-item {{ Route::is('users.index','users.create','users.edit','users.show') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('users.index','users.create','users.edit','users.show') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                User
                <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              @can('user-menu')  
                <li class="nav-item">
                  <a href="{{ route('users.index') }}" class="nav-link {{ Route::is('users.index','users.create','users.edit','users.show') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>User List</p>
                  </a>
                </li>
                @endcan
            </ul>
          </li>
          @endcan
          @can('role-menu')
          <li class="nav-item {{ Route::is('roles.index','roles.create','roles.edit','roles.show') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('roles.index','roles.create','roles.edit','roles.show') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-lock"></i>
                <p>
                Role
                <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
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
          @can('setting-menu')  
          <li class="nav-item {{ Route::is('company-information.index','company-information.import','company-information.export') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('company-information.index','company-information.import','company-information.export') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                Settings
                <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
              @can('setting-information')  
                <li class="nav-item">
                  <a href="{{ route('company-information.index') }}" class="nav-link {{ Route::is('company-information.index') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Company Information</p> 
                  </a>
                </li>
                @endcan
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
                      <p>Database Backup</p> 
                  </a>
                </li>
            </ul>
          </li>
          @endcan
       </ul>
     </nav>
     <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
 </aside>

