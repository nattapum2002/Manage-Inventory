<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div class="brand-link d-flex justify-content-between align-items-center">
        <a href="{{ route('Dashboard.' . Auth::user()->user_type) }}" class="brand-link">
            {{-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"> --}}
            <span class="brand-text font-weight-light">MIS</span>
        </a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('backend/dist/img/AdminLTELogo.png') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('Profile') }}" class="d-block">Admin</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('ShowStock')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            สินค้าในคลัง
                        </p>
                    </a>
                </li>
                @if (Auth::user()->user_type == 'Admin')
                    <li class="nav-item">
                        <a href="{{ route('ManageLockStock') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Manage Lock Stock
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ManageQueue') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Manage Queue
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ManageShift') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Manage Shift
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ManageStock') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Manage Stock
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ManageUsers') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Manage Users
                            </p>
                        </a>
                    </li>
                @elseif (Auth::user()->user_type == 'Manager')
                    <li class="nav-item">
                        <a href="{{ route('ProductStore') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Product Store
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ProductStock') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Product Stock
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('CustomerOrder') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Customer Order
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('Pallet') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Pallet
                            </p>
                        </a>
                    </li>
                @elseif (Auth::user()->user_type == 'User')
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
