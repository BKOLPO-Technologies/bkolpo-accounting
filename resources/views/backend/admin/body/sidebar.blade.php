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
         <a href="{{ route('admin.dashboard') }}" class="d-block">Super Admin</a>
       </div>
     </div>

     <!-- SidebarSearch Form -->
     <!-- Sidebar Menu -->
     <nav class="mt-2">
       <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         <li class="nav-item menu-open">
           <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
             <i class="nav-icon fas fa-tachometer-alt"></i>
             <p>
               Dashboard
             </p>
           </a>
         </li>

         <!-- ---Branch--- -->
         <li class="nav-item {{ Route::is('branch.admin.branch', 'branch.admin.create', 'branch.admin.trashed') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('branch.admin.branch', 'branch.admin.create', 'branch.admin.trashed') ? 'active' : '' }}">
              <i class="nav-icon fas fa-project-diagram"></i>
              <p>
                Branch
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('branch.admin.branch') }}" class="nav-link {{ Route::is('branch.admin.branch') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('branch.admin.create') }}" class="nav-link {{ Route::is('branch.admin.create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('branch.admin.trashed') }}" class="nav-link {{ Route::is('branch.admin.trashed') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Trashed</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- --- -->

          <!-- ---Ledger--- -->
          <li class="nav-item {{ Route::is('admin.ledgergroup*', 'admin.ledgername*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('admin.ledgergroup*', 'admin.ledgername*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                Ledger
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item {{ Route::is('admin.ledgergroup*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.ledgergroup*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Group
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('admin.ledgergroup') }}" class="nav-link {{ Route::is('admin.ledgergroup') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>All</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.ledgergroupcreate') }}" class="nav-link {{ Route::is('admin.ledgergroupcreate') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Create</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.ledgergrouptrashed') }}" class="nav-link {{ Route::is('admin.ledgergrouptrashed') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Trashed</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item {{ Route::is('admin.ledgername*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.ledgername*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Name
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('admin.ledgername') }}" class="nav-link {{ Route::is('admin.ledgername') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>All</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.ledgernamecreate') }}" class="nav-link {{ Route::is('admin.ledgernamecreate') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Create</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.ledgernametrashed') }}" class="nav-link {{ Route::is('admin.ledgernametrashed') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Trashed</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <!-- -------- -->

          <!-- ---Bank Cash--- -->
          <li class="nav-item {{ Route::is('admin.bankcash*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('admin.bankcash*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-university"></i>
              <p>
                Bank Cash
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.bankcash') }}" class="nav-link {{ Route::is('admin.bankcash') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.bankcash.create') }}" class="nav-link {{ Route::is('admin.bankcash.create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Create</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.bankcash.trashed') }}" class="nav-link {{ Route::is('admin.bankcash.trashed') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Trashed</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="{{ route('admin.bankcash.invoice') }}" class="nav-link {{ Route::is('admin.bankcash.invoice') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Invoice</p>
                </a>
              </li> -->
            </ul>
          </li>
          <!-- ---------------- -->

          <!-- ---Invoice--- -->
          <li class="nav-item {{ Route::is('admin.invoice*') ? 'menu-open' : '' }}">
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
          </li>
          <!-- ---------------- -->

          <!-- ---Project--- -->
          <li class="nav-item {{ Route::is('admin.project*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('admin.project*') ? 'active' : '' }}">
              <i class="nav-icon fa-solid fa-file-invoice"></i>
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
          </li>
          <!-- ---------------- -->

          <!-- ---Suppliers--- -->
          <li class="nav-item {{ Route::is('admin.supplier*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('admin.supplier*') ? 'active' : '' }}">
                <i class="nav-icon fa-solid fa-file-invoice"></i>
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
                    <a href="{{ route('admin.supplier.index') }}" class="nav-link {{ Route::is('admin.supplier.index') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Suppliers</p>
                    </a>
                </li>
            </ul>
          </li>


          <!-- ---------------- -->

         <li class="nav-item">
           <a href="#" class="nav-link">
             <i class="nav-icon fas fa-receipt"></i>
             <p>
               Voucher
               <i class="fas fa-angle-left right"></i>
             </p>
           </a>
           <ul class="nav nav-treeview">
             <li class="nav-item">
               <a href="#" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>
                   Credit
                   <i class="fas fa-angle-left right"></i>
                 </p>
               </a>
               <ul class="nav nav-treeview" style="display: none;">
                 <li class="nav-item">
                   <a href="#" class="nav-link">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Credit List</p>
                   </a>
                 </li>
               </ul>
             </li>
             <li class="nav-item">
               <a href="#" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>
                   Debit
                   <i class="fas fa-angle-left right"></i>
                 </p>
               </a>
               <ul class="nav nav-treeview" style="display: none;">
                 <li class="nav-item">
                   <a href="#" class="nav-link">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Debit List</p>
                   </a>
                 </li>
               </ul>
             </li>
             <li class="nav-item">
               <a href="#" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>
                   Journal
                   <i class="fas fa-angle-left right"></i>
                 </p>
               </a>
               <ul class="nav nav-treeview" style="display: none;">
                 <li class="nav-item">
                   <a href="#" class="nav-link">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Journal List</p>
                   </a>
                 </li>
               </ul>
             </li>
             <li class="nav-item">
               <a href="#" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>
                   Contra
                   <i class="fas fa-angle-left right"></i>
                 </p>
               </a>
               <ul class="nav nav-treeview" style="display: none;">
                 <li class="nav-item">
                   <a href="#" class="nav-link">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Contra List</p>
                   </a>
                 </li>
               </ul>
             </li>
           </ul>
         </li>
         <li class="nav-item">
           <a href="#" class="nav-link">
             <i class="nav-icon fas fa-receipt"></i>
             <p>
               Report
               <i class="fas fa-angle-left right"></i>
             </p>
           </a>
           <ul class="nav nav-treeview">
             <li class="nav-item">
               <a href="#" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>
                   Accounts
                   <i class="fas fa-angle-left right"></i>
                 </p>
               </a>
               <ul class="nav nav-treeview" style="display: none;">
                 <li class="nav-item">
                   <a href="#" class="nav-link">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Ledger</p>
                   </a>
                 </li>
                 <li class="nav-item">
                   <a href="#" class="nav-link">
                     <i class="far fa-circle nav-icon"></i>
                     <p>Trial Balance</p>
                   </a>
                 </li>
                 <li class="nav-item">
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
                     <p>Balance Sheet</p>
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
                 </li>
               </ul>
             </li>
             <li class="nav-item">
               <a href="#" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>
                   General
                   <i class="fas fa-angle-left right"></i>
                 </p>
               </a>
               <ul class="nav nav-treeview" style="display: none;">
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
             </li>
           </ul>
         </li>
         <li class="nav-item">
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
         </li>
         <li class="nav-item {{ Route::is('users.index','users.create','users.edit','users.show') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('users.index','users.create','users.edit','users.show') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                User
                <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('users.index') }}" class="nav-link {{ Route::is('users.index','users.create','users.edit','users.show') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>User List</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user-lock"></i>
                <p>
                Role
                <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('roles.index') }}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Role List</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item {{ Route::is('company-information.index') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('company-information.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                Settings
                <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('company-information.index') }}" class="nav-link {{ Route::is('company-information.index') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Company Information</p>
                  </a>
                </li>
            </ul>
          </li>
       </ul>
     </nav>
     <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
 </aside>

