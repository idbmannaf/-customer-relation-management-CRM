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
                <li class="nav-item {{ session('lsbm') == 'employee' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('employee.dashboard') }}"
                                class="nav-link {{ session('lsbsm') == 'employeeDashboard' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Dashboard') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @if (auth()->user()->employee->team_admin && auth()->user()->employee->company->account_maintain_permission)
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
                                <a href="{{ route('employee.billCollection', ['type' => 'assign']) }}"
                                    class="nav-link {{ session('lsbsm') == 'billCollection_assign' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Assign Collection') }}</p>
                                </a>
                                {{-- <a href="{{ route('employee.billCollection', ['type' => 'collections']) }}"
                                    class="nav-link {{ session('lsbsm') == 'billCollection_collections' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Collections') }}</p>
                                </a> --}}
                            </li>

                        </ul>
                    </li>
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
                                <a href="{{ route('employee.collectionList', ['type' => 'pending']) }}"
                                    class="nav-link {{ session('lsbsm') == 'collectionList_pending' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Pending') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.collectionList', ['type' => 'approved']) }}"
                                    class="nav-link {{ session('lsbsm') == 'collectionList_approved' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Received') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
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
                                <a href="{{ route('employee.convayancesBillPayment', ['type' => 'paid']) }}"
                                    class="nav-link {{ session('lsbsm') == 'convayancesBillPayment_paid' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Paid') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.convayancesBillPayment', ['type' => 'unpaid']) }}"
                                    class="nav-link {{ session('lsbsm') == 'convayancesBillPayment_unpaid' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Make Payment') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                @if (auth()->user()->employee->team_admin)
                    <li class="nav-item {{ session('lsbm') == 'team' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                My Team
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            @if (auth()->user()->employee->team_admin)
                                <li class="nav-item">
                                    <a href="{{ route('employee.myTeam') }}"
                                        class="nav-link {{ session('lsbsm') == 'myTeam' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('All Team') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('employee.myTeamAdd') }}"
                                        class="nav-link {{ session('lsbsm') == 'myTeamAdd' ? ' active ' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Create Team Member') }}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <li class="nav-item {{ session('lsbm') == 'customers' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            My Customers
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('employee.myCustomers') }}"
                                class="nav-link {{ session('lsbsm') == 'myCustomers' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('My Customers') }}</p>
                            </a>
                        </li>
                        @if (auth()->user()->employee->team_admin)
                            <li class="nav-item">
                                <a href="{{ route('employee.othersCustomers') }}"
                                    class="nav-link {{ session('lsbsm') == 'othersCustomers' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Others Customers') }}</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                @if (auth()->user()->employee->team_admin)
                    <li class="nav-item {{ session('lsbm') == 'attendance' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Attendances
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('employee.myEmployeeAttandance') }}"
                                    class="nav-link {{ session('lsbsm') == 'allAttendance' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Today') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.myEmployeeAttendanceHistory') }}"
                                    class="nav-link {{ session('lsbsm') == 'attendanceHistory' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('History') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.myEmployeeAttendanceReport') }}"
                                    class="nav-link {{ session('lsbsm') == 'attendanceReport' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Report') }}</p>
                                </a>
                            </li>


                        </ul>
                    </li>


                    {{-- <li class="nav-item {{ session('lsbm') == 'sales' ? ' menu-open ' : '' }}">
                <a href="#" class="nav-link ">
                    <i class="nav-icon 	fas fa-comment-dollar"></i>
                    <p>
                        Sales
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('employee.myEmployeeVisitSale', auth()->user()->employee) }}"
                            class="nav-link {{ session('lsbsm') == 'allSales' ? ' active ' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('All Sales') }}</p>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item {{ session('lsbm') == 'collections' ? ' menu-open ' : '' }}">
                <a href="#" class="nav-link ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Collections
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('employee.myEmployeeVisitCollection', auth()->user()->employee) }}"
                            class="nav-link {{ session('lsbsm') == 'allCollections' ? ' active ' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('All Collection') }}</p>
                        </a>
                    </li>

                </ul>
            </li> --}}
                @endif

                <li class="nav-item {{ session('lsbm') == 'serviceColls' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Service Call
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('employee.calls') }}"
                                class="nav-link {{ session('lsbsm') == 'all_serviceColls' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('All Calls') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.calls', ['type' => 'pending']) }}"
                                class="nav-link {{ session('lsbsm') == 'pending_serviceColls' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Pending Calls') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.calls', ['type' => 'approved']) }}"
                                class="nav-link {{ session('lsbsm') == 'aproved_serviceColls' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Approved Calls') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.calls', ['type' => 'done']) }}"
                                class="nav-link {{ session('lsbsm') == 'done_serviceColls' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Done Calls') }}</p>
                            </a>
                        </li>
                        @if (auth()->user()->employee->team_admin)
                            <li class="nav-item">
                                <a href="{{ route('employee.referanceCall') }}"
                                    class="nav-link {{ session('lsbsm') == 'referanceCall' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Referance Calls') }}</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
                <li class="nav-item {{ session('lsbm') == 'visitPlan' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Visit Plans
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('employee.customerVisit.index', ['status' => 'pending']) }}"
                                class="nav-link {{ session('lsbsm') == 'visitPlan_pending' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Pending') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.customerVisit.index', ['status' => 'approved']) }}"
                                class="nav-link {{ session('lsbsm') == 'visitPlan_approved' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Approved') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.customerVisit.index', ['status' => 'completed']) }}"
                                class="nav-link {{ session('lsbsm') == 'visitPlan_completed' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Completed') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

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
                            <a href="{{ route('employee.allOfMyTeamMemberVisits', ['type' => 'today']) }}"
                                class="nav-link {{ session('lsbsm') == 'visit_today' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Today') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.allOfMyTeamMemberVisits', ['type' => 'pending']) }}"
                                class="nav-link {{ session('lsbsm') == 'visit_pending' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Pending') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.allOfMyTeamMemberVisits', ['type' => 'approved']) }}"
                                class="nav-link {{ session('lsbsm') == 'visit_approved' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Approved') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.allOfMyTeamMemberVisits', ['type' => 'completed']) }}"
                                class="nav-link {{ session('lsbsm') == 'visit_completed' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Completed') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.allOfMyTeamMemberVisits') }}"
                                class="nav-link {{ session('lsbsm') == 'allVisit' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('All Visits') }}</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ session('lsbm') == 'muCustomerOffers' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Customer Offer/Quotation
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('employee.myCustomerOffers', ['type' => 'approved']) }}"
                                class="nav-link {{ session('lsbsm') == 'offers_approved' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Approved Offers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.myCustomerOffers', ['type' => 'pending']) }}"
                                class="nav-link {{ session('lsbsm') == 'offers_pending' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Pending Offers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.myCustomerOffers', ['type' => 'rejected']) }}"
                                class="nav-link {{ session('lsbsm') == 'offers_rejected' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Rejected Offers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.myCustomerOffers', ['type' => 'customer_approved']) }}"
                                class="nav-link {{ session('lsbsm') == 'offers_customer_approved' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Customer Approved Offers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.myCustomerOffers', ['type' => 'customer_not_approved']) }}"
                                class="nav-link {{ session('lsbsm') == 'offers_customer_not_approved' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Customer Not Approved') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>


                @if (auth()->user()->employee->team_admin && auth()->user()->employee->company->logo_and_req_permission)
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
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'spear_parts', 'status' => 'pending']) }}"
                                            class="nav-link {{ session('lsbssm') == 'spear_parts_pending' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Pending') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'spear_parts', 'status' => 'reviewed']) }}"
                                            class="nav-link {{ session('lsbssm') == 'spear_parts_reviewed' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Reviewed') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'spear_parts', 'status' => 'approved']) }}"
                                            class="nav-link {{ session('lsbssm') == 'spear_parts_approved' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Approved') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'spear_parts', 'status' => 'done']) }}"
                                            class="nav-link {{ session('lsbssm') == 'spear_parts_done' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Done') }}</p>
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
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'product', 'status' => 'pending']) }}"
                                            class="nav-link {{ session('lsbssm') == 'product_pending' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Pending') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'product', 'status' => 'reviewed']) }}"
                                            class="nav-link {{ session('lsbssm') == 'product_reviewed' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Reviewed') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'product', 'status' => 'approved']) }}"
                                            class="nav-link {{ session('lsbssm') == 'product_approved' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Approved') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'product', 'status' => 'done']) }}"
                                            class="nav-link {{ session('lsbssm') == 'spear_parts_done' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Done') }}</p>
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
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'inhouse_product', 'status' => 'pending']) }}"
                                            class="nav-link {{ session('lsbssm') == 'inhouse_product_pending' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Pending') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'inhouse_product', 'status' => 'reviewed']) }}"
                                            class="nav-link {{ session('lsbssm') == 'inhouse_product_reviewed' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Reviewed') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('employee.requisitionIndex', ['type' => 'inhouse_product', 'status' => 'approved']) }}"
                                            class="nav-link {{ session('lsbssm') == 'inhouse_product_approved' ? ' active ' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>{{ __('Approved') }}</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="nav-item {{ session('lsbm') == 'challanInvoice' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Challan & Invoice
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('employee.chalanAndInvoice', ['type' => 'challan']) }}"
                                class="nav-link {{ session('lsbsm') == 'challanInvoice_challan' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Challan') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.chalanAndInvoice', ['type' => 'invoice']) }}"
                                class="nav-link {{ session('lsbsm') == 'challanInvoice_invoice' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Invoice') }}</p>
                            </a>
                        </li>


                    </ul>
                </li>

                @if (auth()->user()->employee->team_admin && auth()->user()->employee->company->inventory_maintain_permission)
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
                                <a href="{{ route('employee.inventoryMaintain', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spear Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.inventoryMaintain', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.inventoryMaintain', ['type' => 'inhouse_product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'inhouse_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Inhouse Products') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

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
                            <a href="{{ route('employee.readyToReceiveProduct', ['type' => 'spear_parts']) }}"
                                class="nav-link {{ session('lsbsm') == 'RtR_spear_parts' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Spear Parts') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.readyToReceiveProduct', ['type' => 'product']) }}"
                                class="nav-link {{ session('lsbsm') == 'RtR_product' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Product') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.readyToReceiveProduct', ['type' => 'inhouse_product']) }}"
                                class="nav-link {{ session('lsbsm') == 'RtR_inhouse_product' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Inhouse Products') }}</p>
                            </a>
                        </li>

                    </ul>
                </li>
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
                            <a href="{{ route('employee.receiveProductForStockManage', ['type' => 'spear_parts']) }}"
                                class="nav-link {{ session('lsbsm') == 'receiveProducts_spear_parts' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Spear Parts') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.receiveProductForStockManage', ['type' => 'product']) }}"
                                class="nav-link {{ session('lsbsm') == 'receiveProduct_product' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Product') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.addReceiveProductForStockManage') }}"
                                class="nav-link {{ session('lsbsm') == 'add_new_product' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Add New') }}</p>
                            </a>
                        </li>


                    </ul>
                </li>
                @if (auth()->user()->employee->team_admin && auth()->user()->employee->company->inventory_maintain_permission)
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
                                <a href="{{ route('employee.readyForApproveReceiveProductForStockManage', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'changeStatusReceiveProductForStockManage_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spare Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.readyForApproveReceiveProductForStockManage', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'changeStatusReceiveProductForStockManage_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @if (!auth()->user()->employee->company->access_all_call_visit_plan_without_call ||
                    !auth()->user()->employee->team_admin_id)
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
                                <a href="{{ route('employee.unUsedProduct', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'UU_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spear Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.unUsedProduct', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'UU_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.unUsedProduct', ['type' => 'inhouse_product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'UU_inhouse_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Inhouse Products') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif
                @if (auth()->user()->employee->team_admin && auth()->user()->employee->company->inventory_maintain_permission)
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
                                <a href="{{ route('employee.receiveUnusedProducts', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RUP_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spear Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.receiveUnusedProducts', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RUP_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.receiveUnusedProducts', ['type' => 'inhouse_product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RUP_inhouse_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Inhouse Products') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @if (auth()->user()->employee->team_admin &&
                    auth()->user()->employee->company->store_damage_product_assign_permission)
                    <li class="nav-item {{ session('lsbm') == 'receivedProductFromUnused' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Danmaged Product Assign
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('employee.showReceivedProductInServiceTeamHead', ['type' => 'spear_parts']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RPOU_spear_parts' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Spear Parts') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.showReceivedProductInServiceTeamHead', ['type' => 'product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RPOU_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.showReceivedProductInServiceTeamHead', ['type' => 'inhouse_product']) }}"
                                    class="nav-link {{ session('lsbsm') == 'RPOU_inhouse_product' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Inhouse Products') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @if (auth()->user()->employee->team_admin && auth()->user()->employee->company->inventory_maintain_permission)
                    <li class="nav-item {{ session('lsbm') == ' warrantyClaim' ? ' menu-open ' : '' }}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Warranty Claim
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('employee.warrantyClaim', ['status' => 'pending']) }}"
                                    class="nav-link {{ session('lsbsm') == 'warrantyClaim_pending' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Pending') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.warrantyClaim', ['status' => 'confirmed']) }}"
                                    class="nav-link {{ session('lsbsm') == 'warrantyClaim_confirmed' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Confirmed') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.warrantyClaim', ['status' => 'reviewed']) }}"
                                    class="nav-link {{ session('lsbsm') == 'warrantyClaim_reviewed' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Reviewed') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.warrantyClaim', ['status' => 'approved']) }}"
                                    class="nav-link {{ session('lsbsm') == 'warrantyClaim_approved' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Approved') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif


                @if (!auth()->user()->employee->company->access_all_call_visit_plan_without_call ||
                    !auth()->user()->employee->team_admin_id)
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
                                <a href="{{ route('employee.ProductOfRepairRecharge', ['status' => 'repair']) }}"
                                    class="nav-link {{ session('lsbsm') == 'repairRecharge_repair' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Repair') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.ProductOfRepairRecharge', ['status' => 'recharge']) }}"
                                    class="nav-link {{ session('lsbsm') == 'repairRecharge_recharge' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Recharge') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (!auth()->user()->employee->company->access_all_call_visit_plan_without_call ||
                    !auth()->user()->employee->team_admin_id)
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
                                <a href="{{ route('employee.oldStockProduct', ['status' => 'repair']) }}"
                                    class="nav-link {{ session('lsbsm') == 'OS_repair' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Repair') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.oldStockProduct', ['status' => 'recharge']) }}"
                                    class="nav-link {{ session('lsbsm') == 'OS_recharge' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Recharge') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.oldStockProduct', ['status' => 'bad']) }}"
                                    class="nav-link {{ session('lsbsm') == 'OS_bad' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bad') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('employee.oldStockProduct', ['status' => 'reuse']) }}"
                                    class="nav-link {{ session('lsbsm') == 'OS_reuse' ? ' active ' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Reuse') }}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

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
                            <a href="{{ route('employee.allConvayances', ['type' => 'pending']) }}"
                                class="nav-link {{ session('lsbsm') == 'CB_pending' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Pending') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.allConvayances', ['type' => 'approved']) }}"
                                class="nav-link {{ session('lsbsm') == 'CB_approved' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Approved') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.allConvayances', ['type' => 'rejected']) }}"
                                class="nav-link {{ session('lsbsm') == 'CB_rejected' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Rejected') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.allConvayances', ['type' => 'paid']) }}"
                                class="nav-link {{ session('lsbsm') == 'CB_paid' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Paid') }}</p>
                            </a>
                        </li>


                    </ul>
                </li>
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
                            <a href="{{ route('employee.receivedCustomerRequestProduct', ['type' => 'unsent']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_unsent' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Unsent') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.receivedCustomerRequestProduct', ['type' => 'not_received']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_not_received' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Not Received') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.receivedCustomerRequestProduct', ['type' => 'received']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_received' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Received') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.receivedCustomerRequestProduct', ['type' => 'ready_for_delivered']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_ready_for_delivered' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Ready For Delivered') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('employee.receivedCustomerRequestProduct', ['type' => 'delivered']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_delivered' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Delivered') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('employee.receivedCustomerRequestProduct', ['type' => 'customer_received']) }}"
                                class="nav-link {{ session('lsbsm') == 'receivedCustomerRequestProduct_customer_received' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Customer Received') }}</p>
                            </a>
                        </li>



                    </ul>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('employee.myLocationHistory') }}"
                        class="nav-link {{ session('lsbsm') == 'myLocationHistory' ? ' active ' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('Location History') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employee.myAttandance') }}"
                        class="nav-link {{ session('lsbsm') == 'myAttandance' ? ' active ' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('Attendance History') }}</p>
                    </a>
                </li> --}}


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
