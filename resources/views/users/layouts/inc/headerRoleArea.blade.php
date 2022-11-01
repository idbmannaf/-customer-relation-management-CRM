<ul class="navbar-nav ml-auto">

    <li class="nav-item dropdown">
        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"
            class="nav-link dropdown-toggle">
            @if (auth()->user())
                @if (auth()->user()->employee)
                    {{auth()->user()->employee->name}}
                    @elseif (auth()->user()->customer)
                    {{auth()->user()->customer->customer_name}}
                    @else
                    {{ auth()->user()->name }}
                @endif
            @endif
             <span class="caret"></span>
        </a>
        {{-- <a class="nav-link" data-toggle="dropdown" href="#">{{ auth()->user()->name }}
        </a> --}}

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            @if (count(auth()->user()->roles) > 0)

                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item"> <i
                            class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard </a>
                    <div class="dropdown-divider"></div>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item"> <i
                            class="fas fa-tachometer-alt mr-2"></i>Dashboard </a>
                    <div class="dropdown-divider"></div>
                @endif
            @endif

            @if (auth()->user()->employee)
                <a href="{{ route('employee.dashboard') }}" class="dropdown-item"> <i
                        class="fas fa-tachometer-alt mr-2"></i>Employee Dashboard </a>
                <div class="dropdown-divider"></div>
            @endif

            @if (auth()->user()->customer)
                <a href="{{ route('customer.dashboard') }}" class="dropdown-item"> <i
                        class="fas fa-tachometer-alt mr-2"></i>Client Dashboard </a>
                <div class="dropdown-divider"></div>
            @endif

            @if (count(auth()->user()->roles) > 0)
                <a href="{{ route('admin.myProfile') }}" class="dropdown-item"> <i
                        class="fas fa-tachometer-alt mr-2"></i> My Profile </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.editMyProfile') }}" class="dropdown-item"> <i
                        class="fas fa-tachometer-alt mr-2"></i> Update My Profile
                </a>
            @elseif(auth()->user()->employee)
                <a href="{{ route('employee.myProfile') }}" class="dropdown-item"> <i
                        class="fas fa-tachometer-alt mr-2"></i> My Profile </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('employee.editMyProfile') }}" class="dropdown-item"> <i
                        class="fas fa-tachometer-alt mr-2"></i> Update My Profile
                </a>
            @elseif(auth()->user()->customer)
                <a href="{{ route('customer.myProfile') }}" class="dropdown-item"> <i
                        class="fas fa-tachometer-alt mr-2"></i> My Profile </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('customer.editMyProfile') }}" class="dropdown-item"> <i
                        class="fas fa-tachometer-alt mr-2"></i> Update My Profile
                </a>
            @else
                <a href="{{ route('user.dashboard') }}" class="dropdown-item"> <i
                        class="fas fa-tachometer-alt mr-2"></i> My Profile </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('user.editUser') }}" class="dropdown-item"> <i
                        class="fas fa-tachometer-alt mr-2"></i> Update My Profile
                </a>
            @endif

            @auth
                {{-- <a href="#" class="dropdown-item"> <i class="fas fa-tachometer-alt mr-2"></i> My Profile </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item"> <i class="fas fa-tachometer-alt mr-2"></i> Update My Profile </a> --}}
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endauth
        </div>
    </li>
</ul>
