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
                            <a href="{{ route('customer.dashboard') }}"
                                class="nav-link {{ session('lsbsm') == 'profile' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Dashboard') }}</p>
                            </a>
                        </li>
                    </ul>

                </li>

                <li class="nav-item {{ session('lsbm') == 'colls' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Call Assigns/Task
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('customer.calls') }}"
                                class="nav-link {{ session('lsbsm') == 'colls' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('All Calls') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ session('lsbm') == 'offers' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Offer Quotation
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('customer.offers') }}"
                                class="nav-link {{ session('lsbsm') == 'offers_all' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('All Offers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.offers', ['type' => 'not_accepted']) }}"
                                class="nav-link {{ session('lsbsm') == 'offers_not_accepted' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Not Accepted Offers') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.offers', ['type' => 'accepted']) }}"
                                class="nav-link {{ session('lsbsm') == 'offers_accepted' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Accepted Offers') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
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
                            <a href="{{ route('customer.chalanAndInvoice', ['type' => 'challan']) }}"
                                class="nav-link {{ session('lsbsm') == 'challanInvoice_challan' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Challan') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.chalanAndInvoice', ['type' => 'invoice']) }}"
                                class="nav-link {{ session('lsbsm') == 'challanInvoice_invoice' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Invoice') }}</p>
                            </a>
                        </li>


                    </ul>
                </li>
                <li class="nav-item {{ session('lsbm') == 'sendTheProductInhouse' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Send product In House

                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('customer.sendTheProductInhouse', ['type' => 'sent']) }}"
                                class="nav-link {{ session('lsbsm') == 'sendTheProductInhouse_sent' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Sent Product') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.sendTheProductInhouse', ['type' => 'unsent']) }}"
                                class="nav-link {{ session('lsbsm') == 'sendTheProductInhouse_unsent' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Unsent Products') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.sendTheProductInhouse', ['type' => 'received']) }}"
                                class="nav-link {{ session('lsbsm') == 'sendTheProductInhouse_received' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Received Products') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.sendTheProductInhouse', ['type' => 'delivered']) }}"
                                class="nav-link {{ session('lsbsm') == 'sendTheProductInhouse_delivered' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Delivered Products') }}</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-item {{ session('lsbm') == 'history' ? ' menu-open ' : '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Transaction History
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('customer.transactionHistory') }}"
                                class="nav-link {{ session('lsbsm') == 'challanInvoice_challan' ? ' active ' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Transaction History') }}</p>
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
