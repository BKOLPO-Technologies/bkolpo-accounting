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
              <i class="nav-icon fas fa-book"></i>
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
              $isSupplierActive = Route::is('admin.supplier.index','admin.supplier.create','admin.supplier.view','admin.supplier.edit');
              $isClientActive = Route::is('admin.client.index','admin.client.create','admin.client.view','admin.client.edit');
              $isProjectActive = Route::is('projects.index', 'projects.create', 'projects.show', 'projects.edit');
              $isSalesActive = Route::is('admin.sale.index','admin.sale.create','admin.sale.view','admin.sale.edit','quotations.index','quotations.create','quotations.edit','quotations.show','outcoming.chalan.index','outcoming.chalan.create','outcoming.chalan.show','outcoming.chalan.edit','receipt.payment.index','receipt.payment.create','stock.out','stock.out.view');
              $isPurchaseActive = Route::is('admin.purchase.index','admin.purchase.create','admin.purchase.view','admin.purchase.edit','workorders.index','workorders.create','workorders.edit','workorders.show','incoming.chalan.index','incoming.chalan.create','incoming.chalan.show','incoming.chalan.edit','sale.payment.index','sale.payment.create','stock.in','stock.in.view');
          @endphp

          @can('report-menu')  
            <li class="nav-item {{ $isReportActive ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ $isReportActive ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-line"></i>
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
                        </ul>
                    </li>
                    @endcan
                </ul>
            </li>
          @endcan

          <!-- Category -->
          <li class="nav-item {{ Route::is('admin.category*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('admin.category*') ? 'active' : '' }}">
                {{-- <i class="far fa-circle nav-icon"></i> --}}
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
          </li>

          <!-- Product -->
          <li class="nav-item {{ Route::is('admin.product*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ Route::is('admin.product*') ? 'active' : '' }}">
                  {{-- <i class="far fa-circle nav-icon"></i> --}}
                  <i class="nav-icon fas fa-box"></i>
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

          <!-- Supplier -->
          <li class="nav-item {{ $isSupplierActive ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ $isSupplierActive ? 'active' : '' }}">
                  <i class="nav-icon fas fa-truck"></i>
                  <p>
                    Suppliers
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
              
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('admin.supplier.index') }}" class="nav-link {{ Route::is('admin.supplier.index') || Route::is('admin.supplier.view') || Route::is('admin.supplier.edit') || Route::is('admin.supplier.create') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Manage Suppliers</p>
                      </a>
                  </li>
              </ul>
          </li>

          <!-- Clients -->
          <li class="nav-item {{ $isClientActive ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ $isClientActive ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user"></i>
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
                  <a href="{{ route('projects.index') }}" class="nav-link {{ Route::is('projects.index', 'projects.create', 'projects.show', 'projects.edit') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Manage Projects</p>
                  </a>
              </li>
          </ul>
        </li>


          <!-- Sales -->
          <li class="nav-item {{ $isSalesActive ? 'menu-open' : '' }}">
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
                <li class="nav-item">
                  <a href="{{ route('outcoming.chalan.index') }}" 
                    class="nav-link {{ Route::is('outcoming.chalan.index', 'outcoming.chalan.create', 'outcoming.chalan.show', 'outcoming.chalan.edit') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Outgoing Chalan List</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('receipt.payment.index') }}" 
                    class="nav-link {{ Route::is('receipt.payment.index', 'receipt.payment.create') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Payment Receive List</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('stock.out') }}" 
                    class="nav-link {{ Route::is('stock.out', 'stock.out.view') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Stock Out List</p>
                  </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('quotations.index') }}" 
                      class="nav-link {{ Route::is('quotations.index', 'quotations.create', 'quotations.show', 'quotations.edit') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Quotation List</p>
                    </a>
                </li>
              </ul>
          </li>

          <!-- Purchase -->
          <li class="nav-item {{ $isPurchaseActive ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ $isPurchaseActive ? 'active' : '' }}">
                  <i class="nav-icon fas fa-shopping-bag"></i>
                  <p>
                      Purchase
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
              
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('admin.purchase.index') }}" class="nav-link {{ Route::is('admin.purchase.index','admin.purchase.create', 'admin.purchase.show', 'admin.purchase.edit') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Work Order List</p>
                      </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('incoming.chalan.index') }}" class="nav-link {{ Route::is('incoming.chalan.index','incoming.chalan.create', 'incoming.chalan.show', 'incoming.chalan.edit') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Incoming Chalan List</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('sale.payment.index') }}" 
                      class="nav-link {{ Route::is('sale.payment.index', 'sale.payment.create') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Make Payment List</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('stock.in') }}" 
                      class="nav-link {{ Route::is('stock.in', 'stock.in.view') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Stock In List</p>
                    </a>
                  </li>
              </ul>
          </li>

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

          <li class="nav-item {{ Route::is('stock.index', 'stock.show') ? 'menu-open' : '' }}">
                            
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
              
          </li>

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

