<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{ asset('img/logo.png') }}" alt="{{ env('APP_NAME')}}" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{env('APP_NAME')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                           User Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.dashboard') }}"
                                class="nav-link {{ session('lsbsm') == 'viewUser' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('My Profile') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('user.editUser',$user) }}"
                                class="nav-link {{ session('lsbsm') == 'editUser' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Edit My Profile') }}</p>
                            </a>
                        </li>


                    </ul>

                </li>
                @if (auth()->user()->hasTeamAccess())
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                           User Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.viewUser',['user'=>$user->id]) }}"
                                class="nav-link {{ session('lsbsm') == 'viewUser' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Team') }}</p>
                            </a>
                        </li>
                    </ul>

                </li>
                @endif




            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
