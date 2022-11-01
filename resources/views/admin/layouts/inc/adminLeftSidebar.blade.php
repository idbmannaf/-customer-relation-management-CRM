<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{ asset('img/logo.png') }}" alt="{{ env('APP_NAME') }}" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2 ">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-legacy" data-widget="treeview" role="menu"
                data-accordion="true">
                {{-- <li class="nav-item text-center">
                    <a href="javascript:void(0)" class="btn btn-danger exampleModal" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat"
                        class="nav-link {{ session('lsbsm') == 'glocation' ? ' active ' : '' }}">
                        <i class="fas fa-map"></i>
                        <p>{{ __('Add Location') }}</p>
                    </a>
                </li> --}}

                <li class="nav-item {{ session('lsbm') == 'dashboard' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ session('lsbsm') == 'profile' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Dashboard') }}</p>
                            </a>
                        </li>
                    </ul>
                    {{-- <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ session('lsbsm') == 'dashboard' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        @can('website-parameters')
                            <li class="nav-item">
                                <a href="{{ route('admin.websiteparam.index') }}"
                                    class="nav-link {{ session('lsbsm') == 'websiteParam' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Website Parameter</p>
                                </a>
                            </li>
                        @endcan


                    </ul> --}}
                </li>
                @if (auth()->user()->can('holiday') ||
                    auth()->user()->can('holiday-add') ||
                    auth()->user()->can('holiday-update'))
                    <li class="nav-item {{ session('lsbm') == 'holidays' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-solid fa-phone"></i>
                            <p>
                                Holidays
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.holiday.index') }}"
                                    class="nav-link {{ session('lsbsm') == 'all_holiday' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Holidays') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @can('account')
                    <li class="nav-item {{ session('lsbm') == 'billCollection' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Accounts
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.billCollection', ['type' => 'assign']) }}"
                                    class="nav-link {{ session('lsbsm') == 'billCollection_assign' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Assign Collection') }}</p>
                                </a>
                                {{-- <a href="{{ route('admin.billCollection', ['type' => 'collections']) }}"
                                    class="nav-link {{ session('lsbsm') == 'billCollection_collections' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Collections') }}</p>
                                </a> --}}
                            </li>

                        </ul>
                    </li>
                @endcan
                @can('convayances-bill-payment')
                <li class="nav-item {{ session('lsbm') == 'convayancesBillPayment' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Convayance Bill Payment
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.convayancesBillPayment', ['type' => 'paid']) }}"
                                class="nav-link {{ session('lsbsm') == 'convayancesBillPayment_paid' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Paid') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.convayancesBillPayment', ['type' => 'unpaid']) }}"
                                class="nav-link {{ session('lsbsm') == 'convayancesBillPayment_unpaid' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Make Payment') }}</p>
                            </a>
                        </li>

                    </ul>
                </li>
                @endcan

                @can('collection-list')
                    <li class="nav-item {{ session('lsbm') == 'collectionList' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Collection List
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.collectionList', ['type' => 'pending']) }}"
                                    class="nav-link {{ session('lsbsm') == 'collectionList_pending' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Pending') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.collectionList', ['type' => 'approved']) }}"
                                    class="nav-link {{ session('lsbsm') == 'collectionList_approved' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Received') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan

                @if (auth()->user()->can('service-call') ||
                    auth()->user()->can('service-call-add') ||
                    auth()->user()->can('service-call-update'))
                    <li class="nav-item {{ session('lsbm') == 'serviceColls' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-solid fa-phone"></i>
                            <p>
                                Service Call
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.calls') }}"
                                    class="nav-link {{ session('lsbsm') == 'all_serviceColls' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('All Calls') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.calls', ['type' => 'pending']) }}"
                                    class="nav-link {{ session('lsbsm') == 'pending_serviceColls' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Pending Calls') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.calls', ['type' => 'approved']) }}"
                                    class="nav-link {{ session('lsbsm') == 'approved_serviceColls' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Approved Calls') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.calls', ['type' => 'done']) }}"
                                    class="nav-link {{ session('lsbsm') == 'done_serviceColls' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Done Calls') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.refferedCall') }}"
                                    class="nav-link {{ session('lsbsm') == 'reffered_serviceColls' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Reffered Calls') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif


                {{-- <li class="nav-item {{ session('lsbm') == 'saleNcollection' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-comment-dollar"></i>
                        <p>
                            Sales & Collections
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.visitSales') }}"
                                class="nav-link {{ session('lsbsm') == 'allSales' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Sales') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.visitCollections') }}"
                                class="nav-link {{ session('lsbsm') == 'allCollections' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Collections') }}</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}
                @if (auth()->user()->can('role-and-permission'))
                    <li class="nav-item {{ session('lsbm') == 'role' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-user-lock"></i>
                            <p>
                                Role & Permission
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}"
                                    class="nav-link {{ session('lsbsm') == 'allRoles' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('All Roles') }}</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
            <a href="{{ route('admin.roles.create') }}"
                class="nav-link {{ session('lsbsm') == 'roleCreate' ? ' active ' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Create Roles') }}</p>
            </a>
        </li> --}}

                            <li class="nav-item">
                                <a href="{{ route('admin.permissions.index') }}"
                                    class="nav-link {{ session('lsbsm') == 'allPermission' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Permissions</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
            <a href="{{ route('admin.permissions.create') }}"
                class="nav-link {{ session('lsbsm') == 'newPermission' ? ' active ' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>New Permission</p>
            </a>
        </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.assignRole') }}"
                                    class="nav-link {{ session('lsbsm') == 'assignRole' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Assign Role</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif


                {{-- Users --}}
                @if (auth()->user()->can('user-list') ||
                    auth()->user()->can('user-add') ||
                    auth()->user()->can('user-edit') ||
                    auth()->user()->can('user-delete') ||
                    auth()->user()->can('user-with-roles'))
                    <li class="nav-item {{ session('lsbm') == 'users' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('user-list')
                                <li class="nav-item">
                                    <a href="{{ route('admin.user.index') }}"
                                        class="nav-link {{ session('lsbsm') == 'allUsers' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('All Users') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('user-add')
                                <li class="nav-item">
                                    <a href="{{ route('admin.user.create') }}"
                                        class="nav-link {{ session('lsbsm') == 'createUser' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Create User') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('user-with-roles')
                                <li class="nav-item">
                                    <a href="{{ route('admin.user.userWithRoles') }}"
                                        class="nav-link {{ session('lsbsm') == 'userWithRoles' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('User With Roles') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{-- Company --}}
                @if (auth()->user()->can('company-list') ||
                    auth()->user()->can('company-add') ||
                    auth()->user('company-edit')->can('company-delete') ||
                    auth()->user()->can('company-offices') ||
                    auth()->user()->can('company-customers') ||
                    auth()->user()->can('company-employees'))
                    <li class="nav-item {{ session('lsbm') == 'companies' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Companies
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('company-list')
                                <li class="nav-item">
                                    <a href="{{ route('admin.company.index') }}"
                                        class="nav-link {{ session('lsbsm') == 'allCompanies' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('All Companies') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('company-add')
                                <li class="nav-item">
                                    <a href="{{ route('admin.company.create') }}"
                                        class="nav-link {{ session('lsbsm') == 'createCompany' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Create Company') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif


                {{-- Employee --}}
                @if (auth()->user()->can('employee-list') ||
                    auth()->user()->can('employee-add') ||
                    auth()->user()->can('employee-edit') ||
                    auth()->user()->can('employee-delete') ||
                    auth()->user()->can('employee-location') ||
                    auth()->user()->can('employee-attandance') ||
                    auth()->user()->can('employee-bulk-upload'))
                    <li class="nav-item {{ session('lsbm') == 'employees' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Employees
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('employee-list')
                                <li class="nav-item">
                                    <a href="{{ route('admin.employee.index') }}"
                                        class="nav-link {{ session('lsbsm') == 'allEmployees' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('All Employees') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{-- <li class="nav-item {{ session('lsbm') == 'teams' ? ' menu-open ' : '' }}">
                                <a href="#" class="nav-link ">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>
                                        Teams
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.team.index') }}"
                                            class="nav-link {{ session('lsbsm') == 'allTeam' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('All Team') }}</p>
                                        </a>
                                    </li>

                                </ul>
                            </li> --}}
                {{-- Customer Companies --}}
                @if (auth()->user()->can('customer-company') ||
                    auth()->user()->can('customer-company-add') ||
                    auth()->user()->can('customer-company-edit') ||
                    auth()->user()->can('customer-company-delete'))
                    <li class="nav-item {{ session('lsbm') == 'customer_companies' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Customer Companies
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.customer_company.index') }}"
                                    class="nav-link {{ session('lsbsm') == 'all_customer_companies' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('All Companies') }}</p>
                                </a>
                            </li>
                            @if (auth()->user()->can('customer-company-add'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.customer_company.create') }}"
                                        class="nav-link {{ session('lsbsm') == 'create_customer_companies' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Create Company') }}</p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
                {{-- Customer --}}
                @if (auth()->user()->can('customer-list') ||
                    auth()->user()->can('customer-add') ||
                    auth()->user()->can('customer-edit') ||
                    auth()->user()->can('customer-delete') ||
                    auth()->user()->can('customer-bulk-upload'))
                    <li class="nav-item {{ session('lsbm') == 'customers' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Customers
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('customer-list')
                                <li class="nav-item">
                                    <a href="{{ route('admin.customer.index') }}"
                                        class="nav-link {{ session('lsbsm') == 'allCustomers' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('All Customers') }}</p>
                                    </a>
                                </li>
                            @endcan

                            @can('customer-add')
                                <li class="nav-item">
                                    <a href="{{ route('admin.customer.create') }}"
                                        class="nav-link {{ session('lsbsm') == 'addCustomers' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Add Customer') }}</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endif
                @can('customer-offer-quotation')
                    <li class="nav-item {{ session('lsbm') == 'offers' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Customer Offer/Quotation
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.customerOffers', ['type' => 'approved']) }}"
                                    class="nav-link {{ session('lsbsm') == 'customerOffers_approved' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Approved Offers') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.customerOffers', ['type' => 'pending']) }}"
                                    class="nav-link {{ session('lsbsm') == 'customerOffers_pending' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Pending Offers') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.customerOffers', ['type' => 'rejected']) }}"
                                    class="nav-link {{ session('lsbsm') == 'customerOffers_rejected' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Rejected Offers') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.customerOffers', ['type' => 'customer_approved']) }}"
                                    class="nav-link {{ session('lsbsm') == 'customerOffers_customer_approved' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Customer Approved Offers') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.customerOffers', ['type' => 'customer_not_approved']) }}"
                                    class="nav-link {{ session('lsbsm') == 'customerOffers_customer_not_approved' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Customer Not Approved') }}</p>
                                </a>
                            </li>



                        </ul>
                    </li>
                @endcan

                @if (auth()->user()->can('office-location-list') ||
                    auth()->user()->can('office-location-add') ||
                    auth()->user()->can('office-location-edit') ||
                    auth()->user()->can('office-location-delete'))
                    <li class="nav-item {{ session('lsbm') == 'officeLocation' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Office Location
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('office-location-list')
                                <li class="nav-item">
                                    <a href="{{ route('admin.location.index') }}"
                                        class="nav-link {{ session('lsbsm') == 'AllLocations' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Company Locations') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.location.index', ['type' => 'customer']) }}"
                                        class="nav-link {{ session('lsbsm') == 'AllCustomerLocations' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Customer CO Locations') }}</p>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('office-location-add')
                                <li class="nav-item">
                                    <a href="{{ route('admin.location.create') }}"
                                        class="nav-link {{ session('lsbsm') == 'createLocations' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Create Locations') }}</p>
                                    </a>
                                </li>
                            @endcan --}}


                        </ul>
                    </li>
                @endif

                @if (auth()->user()->can('attendance-today') ||
                    auth()->user()->can('attendance-history') ||
                    auth()->user()->can('attendance-report'))
                    <li class="nav-item {{ session('lsbm') == 'attendance' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Attendance
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('attendance-today')
                                <li class="nav-item">
                                    <a href="{{ route('admin.attendance') }}"
                                        class="nav-link {{ session('lsbsm') == 'allAttendance' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Today') }}</p>
                                    </a>
                                </li>
                            @endcan

                            @can('attendance-history')
                                <li class="nav-item">
                                    <a href="{{ route('admin.attendanceHistory') }}"
                                        class="nav-link {{ session('lsbsm') == 'attendanceHistory' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('History') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('attendance-report')
                                <li class="nav-item">
                                    <a href="{{ route('admin.attendanceReport') }}"
                                        class="nav-link {{ session('lsbsm') == 'attendanceReport' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Report') }}</p>
                                    </a>
                                </li>
                            @endcan

                            <li class="nav-item">
                                <a href="{{ route('admin.companyWiseAttendanceReport') }}"
                                    class="nav-link {{ session('lsbsm') == 'companyWiseAttendanceReport' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Company Wise') }}</p>
                                </a>
                            </li>


                        </ul>
                    </li>
                @endif
                @if (auth()->user()->can('visit-plan') ||
                    auth()->user()->can('visit-plan-add') ||
                    auth()->user()->can('visit-plan-update'))
                    <li class="nav-item {{ session('lsbm') == 'visitPlans' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="fab fa-accessible-icon nav-icon"></i>
                            <p>
                                Visit Plans
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.visitPlans', ['status' => 'pending']) }}"
                                    class="nav-link {{ session('lsbsm') == 'visitPlan_pending' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Pending') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.visitPlans', ['status' => 'approved']) }}"
                                    class="nav-link {{ session('lsbsm') == 'visitPlan_approved' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Approved') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.visitPlans', ['status' => 'completed']) }}"
                                    class="nav-link {{ session('lsbsm') == 'visitPlan_completed' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Completed') }}</p>
                                </a>
                            </li>
                        </ul>

                    </li>
                @endif

                @if (auth()->user()->can('visit') ||
                    auth()->user()->can('visit-add') ||
                    auth()->user()->can('visit-update'))
                    <li class="nav-item {{ session('lsbm') == 'visits' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Visits
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.allVisits', ['type' => 'today']) }}"
                                    class="nav-link {{ session('lsbsm') == 'visit_today' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Today') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.allVisits', ['type' => 'pending']) }}"
                                    class="nav-link {{ session('lsbsm') == 'visit_pending' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Pending') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.allVisits', ['type' => 'approved']) }}"
                                    class="nav-link {{ session('lsbsm') == 'visit_approved' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Approved') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.allVisits', ['type' => 'completed']) }}"
                                    class="nav-link {{ session('lsbsm') == 'visit_completed' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Completed') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.allVisits') }}"
                                    class="nav-link {{ session('lsbsm') == 'allVisit' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('All Visits') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                {{-- <li class="nav-item {{ session('lsbm') == 'allVisits' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="fab fa-accessible-icon nav-icon"></i>
                        <p>
                            Visits
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.allVisits', 'today') }}"
                                class="nav-link {{ session('lsbsm') == 'today_visit' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Today Visits') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.allVisits', 'all') }}"
                                class="nav-link {{ session('lsbsm') == 'all_visit' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('All Visits') }}</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}



                @if (auth()->user()->can('product') ||
                    auth()->user()->can('product-add') ||
                    auth()->user()->can('product-update'))
                    <li class="nav-item {{ session('lsbm') == 'products' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="fab fa-accessible-icon nav-icon"></i>
                            <p>
                                Products
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.category.index', ['service_type' => 'products']) }}"
                                    class="nav-link {{ session('lsbsm') == 'productsCategories' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Categories') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.brand.index', ['service_type' => 'products']) }}"
                                    class="nav-link {{ session('lsbsm') == 'productsBrands' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Brands') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.product.index', ['service_type' => 'products']) }}"
                                    class="nav-link {{ session('lsbsm') == 'productsAll' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Products') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                @if (auth()->user()->can('spare-parts') ||
                    auth()->user()->can('spare-parts-add') ||
                    auth()->user()->can('spare-parts-update'))
                    <li class="nav-item {{ session('lsbm') == 'spare_parts' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="fab fa-accessible-icon nav-icon"></i>
                            <p>
                                Spare Parts
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.category.index', ['service_type' => 'spare_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'spare_partsCategories' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Categories') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.brand.index', ['service_type' => 'spare_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'spare_partsBrands' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Brands') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.product.index', ['service_type' => 'spare_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'all_spare_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spare Parts') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                @if (auth()->user()->can('requisition') ||
                    auth()->user()->can('requisition-add') ||
                    auth()->user()->can('requisition-update'))
                    <li class="nav-item {{ session('lsbm') == 'requisitions' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="fab fa-accessible-icon nav-icon"></i>
                            <p>
                                Requisition
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview pl-1">
                            <li class="nav-item {{ session('lsbsm') == 'spear_parts' ? ' menu-open ' : '' }}">
                                <a href="#" class="nav-link ">

                                    <i class="fab fa-accessible-icon nav-icon"></i>
                                    <p>
                                        Spear Parts
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'spear_parts', 'status' => 'pending']) }}"
                                            class="nav-link {{ session('lsbssm') == 'spear_parts_pending' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Pending') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'spear_parts', 'status' => 'reviewed']) }}"
                                            class="nav-link {{ session('lsbssm') == 'spear_parts_reviewed' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Reviewed') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'spear_parts', 'status' => 'approved']) }}"
                                            class="nav-link {{ session('lsbssm') == 'spear_parts_approved' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Approved') }}</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <li class="nav-item {{ session('lsbsm') == 'product' ? ' menu-open ' : '' }}">
                                <a href="#" class="nav-link ">
                                    <i class="fab fa-accessible-icon nav-icon"></i>
                                    <p>
                                        Products Req
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'product', 'status' => 'pending']) }}"
                                            class="nav-link {{ session('lsbssm') == 'product_pending' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Pending') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'product', 'status' => 'reviewed']) }}"
                                            class="nav-link {{ session('lsbssm') == 'product_reviewed' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Reviewed') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'product', 'status' => 'approved']) }}"
                                            class="nav-link {{ session('lsbssm') == 'product_approved' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Approved') }}</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item {{ session('lsbsm') == 'inhouse_product' ? ' menu-open ' : '' }}">
                                <a href="#" class="nav-link ">
                                    <i class="fab fa-accessible-icon nav-icon"></i>
                                    <p>
                                        Inhouse Product
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'inhouse_product', 'status' => 'pending']) }}"
                                            class="nav-link {{ session('lsbssm') == 'inhouse_product_pending' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Pending') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'inhouse_product', 'status' => 'reviewed']) }}"
                                            class="nav-link {{ session('lsbssm') == 'inhouse_product_reviewed' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Reviewed') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'inhouse_product', 'status' => 'approved']) }}"
                                            class="nav-link {{ session('lsbssm') == 'inhouse_product_approved' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Approved') }}</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <li class="nav-item {{ session('lsbsm') == 'warranty_claim' ? ' menu-open ' : '' }}">
                                <a href="#" class="nav-link ">
                                    <i class="fab fa-accessible-icon nav-icon"></i>
                                    <p>
                                        Warranty Claim
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'warranty_claim', 'status' => 'pending']) }}"
                                            class="nav-link {{ session('lsbssm') == 'warranty_claim_pending' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Pending') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'warranty_claim', 'status' => 'confirmed']) }}"
                                            class="nav-link {{ session('lsbssm') == 'warranty_claim_confirmed' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Confirmed
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ') }}
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'warranty_claim', 'status' => 'reviewed']) }}"
                                            class="nav-link {{ session('lsbssm') == 'warranty_claim_reviewed' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Reviewed') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.requisitions', ['type' => 'warranty_claim', 'status' => 'approved']) }}"
                                            class="nav-link {{ session('lsbssm') == 'warranty_claim_approved' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Approved') }}</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
                @can('approved-requisition')
                    <li class="nav-item {{ session('lsbm') == 'inventoryMaintain' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Approved Requisition
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.inventoryMaintain', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'INV_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spear Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.inventoryMaintain', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'INV_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.inventoryMaintain', ['type' => 'inhouse_product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'INV_inhouse_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Inhouse Products') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan

                @can('ready-to-receive')
                    <li class="nav-item {{ session('lsbm') == 'readyToReceiveProducts' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Ready To Receive
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.readyToReceiveProduct', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RtR_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spear Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.readyToReceiveProduct', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RtR_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.readyToReceiveProduct', ['type' => 'inhouse_product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RtR_inhouse_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Inhouse Products') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan
                @can('received-products')
                    <li class="nav-item {{ session('lsbm') == 'receiveProducts' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Received Products
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.receiveProductForStockManage', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'receiveProducts_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spear Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.receiveProductForStockManage', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'receiveProduct_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.addReceiveProductForStockManage') }}"
                                    class="nav-link {{ session('lsbsm') == 'add_new_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Add New') }}</p>
                                </a>
                            </li>


                        </ul>
                    </li>
                @endcan

                @can('received-products-approve')
                    <li
                        class="nav-item {{ session('lsbm') == 'changeStatusReceiveProductForStockManage' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Approved Received Products
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.readyForApproveReceiveProductForStockManage', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'changeStatusReceiveProductForStockManage_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spare Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.readyForApproveReceiveProductForStockManage', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'changeStatusReceiveProductForStockManage_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan

                @can('unused-product')
                    <li class="nav-item {{ session('lsbm') == 'unUsedProducts' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Unused Product
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.unUsedProduct', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'UU_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spear Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.unUsedProduct', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'UU_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.unUsedProduct', ['type' => 'inhouse_product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'UU_inhouse_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Inhouse Products') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan

                @can('unused-product-received')
                    <li class="nav-item {{ session('lsbm') == 'receiveUnusedProducts' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Receive Unused Product
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.receiveUnusedProducts', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RUP_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spear Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.receiveUnusedProducts', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RUP_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.receiveUnusedProducts', ['type' => 'inhouse_product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RUP_inhouse_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Inhouse Products') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan


                @can('damaged-product-assign')
                    <li class="nav-item {{ session('lsbm') == 'receivedProductFromUnused' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Damaged Product Assign
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.showReceivedProductInServiceTeamHead', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RPOU_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spear Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.showReceivedProductInServiceTeamHead', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RPOU_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.showReceivedProductInServiceTeamHead', ['type' => 'inhouse_product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RPOU_inhouse_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Inhouse Products') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan


                @can('repair-and-recharge-product')
                    <li class="nav-item {{ session('lsbm') == 'repairRecharge' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Repair & Recharge P
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.ProductOfRepairRecharge', ['status' => 'repair']) }}"
                                    class="nav-link {{ session('lsbsm') == 'repairRecharge_repair' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Repair') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ProductOfRepairRecharge', ['status' => 'recharge']) }}"
                                    class="nav-link {{ session('lsbsm') == 'repairRecharge_recharge' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Recharge') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @if (auth()->user()->can('challan') ||
                    auth()->user()->can('invoice'))
                    <li class="nav-item {{ session('lsbm') == 'challanInvoice' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Challan & Invoice
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            @can('challan')
                                <li class="nav-item">
                                    <a href="{{ route('admin.chalanAndInvoice', ['type' => 'challan']) }}"
                                        class="nav-link {{ session('lsbsm') == 'challanInvoice_challan' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Challan') }}</p>
                                    </a>
                                </li>
                            @endcan

                            @can('invoice')
                                <li class="nav-item">
                                    <a href="{{ route('admin.chalanAndInvoice', ['type' => 'invoice']) }}"
                                        class="nav-link {{ session('lsbsm') == 'challanInvoice_invoice' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Invoice') }}</p>
                                    </a>
                                </li>
                            @endcan



                        </ul>
                    </li>
                @endif
                @can('old-stock')
                    <li class="nav-item {{ session('lsbm') == 'oldStocks' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Old Stock
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.oldStockProduct', ['status' => 'repair']) }}"
                                    class="nav-link {{ session('lsbsm') == 'OS_repair' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Repair') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.oldStockProduct', ['status' => 'recharge']) }}"
                                    class="nav-link {{ session('lsbsm') == 'OS_recharge' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Recharge') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.oldStockProduct', ['status' => 'bad']) }}"
                                    class="nav-link {{ session('lsbsm') == 'OS_bad' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bad') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.oldStockProduct', ['status' => 'reuse']) }}"
                                    class="nav-link {{ session('lsbsm') == 'OS_reuse' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Reuse') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan
                @can('convayances-bill')
                <li class="nav-item {{ session('lsbm') == 'allConvayances' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Convayance Bill
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.allConvayances', ['type' => 'pending']) }}"
                                class="nav-link {{ session('lsbsm') == 'CB_pending' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Pending') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.allConvayances', ['type' => 'approved']) }}"
                                class="nav-link {{ session('lsbsm') == 'CB_approved' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Approved') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.allConvayances', ['type' => 'rejected']) }}"
                                class="nav-link {{ session('lsbsm') == 'CB_rejected' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Rejected') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.allConvayances', ['type' => 'paid']) }}"
                                class="nav-link {{ session('lsbsm') == 'CB_paid' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Paid') }}</p>
                            </a>
                        </li>


                    </ul>
                </li>
                @endcan


                @if (auth()->user()->can('designation-list') ||
                    auth()->user()->can('designation-add') ||
                    auth()->user()->can('designation-edit') ||
                    auth()->user()->can('designation-delete'))
                    <li class="nav-item {{ session('lsbm') == 'designation' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Designations
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (auth()->user()->can('designation-list') ||
                                auth()->user()->can('designation-add'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.designation.index') }}"
                                        class="nav-link {{ session('lsbsm') == 'allDesignation' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('All Designations') }}</p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif

                @if (auth()->user()->can('department-list') ||
                    auth()->user()->can('department-add') ||
                    auth()->user()->can('department-edit') ||
                    auth()->user()->can('department-delete'))
                    <li class="nav-item {{ session('lsbm') == 'department' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Departments
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (auth()->user()->can('department-list') ||
                                auth()->user()->can('department-add'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.department.index') }}"
                                        class="nav-link {{ session('lsbsm') == 'allDepartment' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('All Departments') }}</p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif



                @can('report')
                    <li class="nav-item {{ session('lsbm') == 'reports' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Reports
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.collectionReport') }}"
                                    class="nav-link {{ session('lsbsm') == 'collectionReport' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Collection') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan

                <li class="nav-item {{ session('lsbm') == 'receivedCustomerRequestProduct' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                           Received Customer Products
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        {{-- <li class="nav-item">
                            <a href="{{ route('employee.receivedCustomerRequestProduct', ['type' => 'sent']) }}"
                                class="nav-link {{ session('lsbsm') == 'CB_sent' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Sent Product') }}</p>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.receivedCustomerRequestProduct', ['type' => 'unsent']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_unsent' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Unsent') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.receivedCustomerRequestProduct', ['type' => 'not_received']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_not_received' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Not Received') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.receivedCustomerRequestProduct', ['type' => 'received']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_received' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Received') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.receivedCustomerRequestProduct', ['type' => 'ready_for_delivered']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_ready_for_delivered' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Ready For Delivered') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.receivedCustomerRequestProduct', ['type' => 'delivered']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_delivered' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Delivered') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.receivedCustomerRequestProduct', ['type' => 'customer_received']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_customer_received' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Customer Received') }}</p>
                            </a>
                        </li>



                    </ul>
                </li>
                {{-- <li class="nav-item {{ session('lsbm') == 'glocation' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Locations
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('glocation') }}"
                                    class="nav-link {{ session('lsbsm') == 'glocation' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('All Locations') }}</p>
                                </a>
                            </li>


                    </ul>
                </li> --}}


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
