 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="index3.html" class="brand-link">
         <span class="brand-text font-weight-light">Products Management</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar Menu -->
         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                 <li class="nav-item">
                     <a href="{{ route('products') }}" class="nav-link">
                         <i class="nav-icon fas fa-chart-pie"></i>
                         <p>
                             Products
                             <i class="right fas fa-angle-left"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview" style="display: block;">
                        <li class="nav-item">
                             <a href="{{ route('products') }}" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>List</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="{{ route('create') }}" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p>Add New</p>
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
