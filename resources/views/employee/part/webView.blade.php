<div class="card">

    <div class="card-body">
        <h4>Service Calls</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.calls', ['type' => 'pending']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-receipt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending</span>
                            <span class="info-box-number">{{ $pending_calls }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.calls', ['type' => 'approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-file-invoice"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Approved</span>
                            <span class="info-box-number">{{ $approved_calls }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.calls', ['type' => 'done']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-file-invoice"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Done</span>
                            <span class="info-box-number">{{ $done_calls }}</span>
                        </div>
                    </div>
                </a>
            </div>
            @if (auth()->user()->employee->team_admin)
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.referanceCall') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-file-invoice"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Referance</span>
                            <span class="info-box-number">{{ $reff_calls }}</span>
                        </div>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
{{-- Visit Plan --}}
<div class="card">
    <div class="card-body">
        <h4>Visit Plan</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.customerVisit.index', ['status' => 'pending']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending</span>
                            <span class="info-box-number">{{ $pending_visit_plan }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.customerVisit.index', ['status' => 'approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Approved</span>
                            <span class="info-box-number">{{ $approved_visit_plan }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.customerVisit.index', ['status' => 'completed']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Completed</span>
                            <span class="info-box-number">{{ $completed_visit_plan }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
{{-- Visit --}}
<div class="card">
    <div class="card-body">
        <h4>Visits</h4>
        <div class="row">

            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.allOfMyTeamMemberVisits', ['status' => 'pending']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending</span>
                            <span class="info-box-number">{{ $total_pending_visit }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.allOfMyTeamMemberVisits', ['status' => 'approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Approved</span>
                            <span class="info-box-number">{{ $total_approved_visit }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.allOfMyTeamMemberVisits', ['status' => 'completed']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Completed</span>
                            <span class="info-box-number">{{ $total_completed_visit }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
{{-- Cusotmer Offer --}}
<div class="card">
    <div class="card-body">
        <h4>Customer Offer Quotation</h4>
        <div class="row">

            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.myCustomerOffers', ['type' => 'pending']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending</span>
                            <span class="info-box-number">{{ $pending_offer_quot }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.myCustomerOffers', ['type' => 'approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Approved</span>
                            <span class="info-box-number">{{ $approved_offer_quot }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.myCustomerOffers', ['type' => 'rejected']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Rejected</span>
                            <span class="info-box-number">{{ $rejected_offer_quot }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.myCustomerOffers', ['type' => 'customer_approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Customer Approved</span>
                            <span class="info-box-number">{{ $customer_approved_offer_quot }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.myCustomerOffers', ['type' => 'customer_not_approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Customer Not Approved</span>
                            <span class="info-box-number">{{ $customer_not_approved_offer_quot }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
{{-- Product Requisition --}}
<div class="card">
    <div class="card-body">
        <h4>Product Requisition</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.requisitionIndex', ['type' => 'product', 'status' => 'pending']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending</span>
                            <span class="info-box-number">{{ $pending_product_rq }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.requisitionIndex', ['type' => 'product', 'status' => 'reviewed']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Reviewed</span>
                            <span class="info-box-number">{{ $reviewed_product_rq }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                @if ($employee->team_admin && $employee->company->inventory_maintain_permission)
                    <a href="{{ route('employee.inventoryMaintain', ['type' => 'product']) }}">
                    @else
                        <a
                            href="{{ route('employee.requisitionIndex', ['type' => 'product', 'status' => 'approved']) }}">
                @endif

                <div class="info-box">
                    <span class="info-box-icon bg-dark"><i class="fab fa-product-hunt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Approved</span>
                        <span class="info-box-number">{{ $approved_product_rq }}</span>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>
</div>
{{-- Spare Parts Requisition --}}
<div class="card">
    <div class="card-body">
        <h4>Spare Parts Requisition</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.allOfMyTeamMemberVisits', ['status' => 'approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-cogs"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending</span>
                            <span class="info-box-number">{{ $pending_sp_rq }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.requisitionIndex', ['type' => 'spear_parts', 'status' => 'reviewed']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-cogs"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Reviewed</span>
                            <span class="info-box-number">{{ $reviewed_sp_rq }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                @if ($employee->team_admin && $employee->company->inventory_maintain_permission)
                    <a href="{{ route('employee.inventoryMaintain', ['type' => 'spear_parts']) }}">
                    @else
                        <a
                            href="{{ route('employee.requisitionIndex', ['type' => 'spear_parts', 'status' => 'approved']) }}">
                @endif

                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-cogs"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Approved </span>
                        <span class="info-box-number">{{ $approved_sp_rq }}</span>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Challan And Invoice --}}
<div class="card">
    <div class="card-body">
        <h4>Challan And Invoice</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.chalanAndInvoice', ['type' => 'challan']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-receipt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Challan</span>
                            <span class="info-box-number">{{ $total_challan }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.chalanAndInvoice', ['type' => 'invoice']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-file-invoice"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Invoice</span>
                            <span class="info-box-number">{{ $total_invoice }}</span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>
{{-- Ready To Received Product/Spare Parts --}}
<div class="card">
    <div class="card-body">
        <h4>Ready To Received Product/Spare Parts</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.readyToReceiveProduct', ['type' => 'spear_parts']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Spare Parts</span>
                            <span class="info-box-number">{{ $r_to_r_sp }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.readyToReceiveProduct', ['type' => 'product']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Products</span>
                            <span class="info-box-number">{{ $r_to_r_p }}</span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Unused Product & Spare Parts --}}
@if (!auth()->user()->employee->company->access_all_call_visit_plan_without_call ||
!auth()->user()->employee->team_admin_id)

<div class="card">
    <div class="card-body">
        <h4>Unused Product & Spare Parts</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.unUsedProduct', ['type' => 'spear_parts ']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Spare Parts</span>
                            <span class="info-box-number">{{ $unused_spear_parts }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.unUsedProduct', ['type' => 'product ']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Product</span>
                            <span class="info-box-number">{{ $unused_products }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Received Unused Product & Spare Parts --}}
@if (auth()->user()->employee->team_admin && auth()->user()->employee->company->inventory_maintain_permission)
<div class="card">
    <div class="card-header">Received Unused Product & Spare Parts</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.receiveUnusedProducts', ['type' => 'spear_parts']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-receipt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Spare Parts</span>
                            <span class="info-box-number">{{ $receiveUnusedspear_parts }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.receiveUnusedProducts', ['type' => 'product']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Product</span>
                            <span class="info-box-number">{{ $receiveUnusedProduct }}</span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>
@endif
{{-- Danmaged Product Assign --}}
@if (auth()->user()->employee->team_admin && auth()->user()->employee->company->store_damage_product_assign_permission)
<div class="card">
    <div class="card-header">Danmaged Product Assign</div>
    <div class="card-body">
        <div class="row">

            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.showReceivedProductInServiceTeamHead', ['type' => 'spear_parts']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-receipt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Spare Parts</span>
                            <span class="info-box-number">{{ $receivedSpearPpartFromUnused }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('employee.showReceivedProductInServiceTeamHead', ['type' => 'product']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Product</span>
                            <span class="info-box-number">{{ $receivedProductFromUnused }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Old Stock --}}
@if (!auth()->user()->employee->company->access_all_call_visit_plan_without_call ||
    !auth()->user()->employee->team_admin_id)
    <div class="card">
        <div class="card-header"> Old Stock</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('employee.oldStockProduct', ['status' => 'repair']) }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="fab fa-buffer"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Repeir</span>
                                <span class="info-box-number">{{ $repair_stock }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('employee.oldStockProduct', ['status' => 'recharge']) }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fab fa-buffer"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Recharge</span>
                                <span class="info-box-number">{{ $recharge_stock }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('employee.oldStockProduct', ['status' => 'bad']) }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fab fa-buffer"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Bad</span>
                                <span class="info-box-number">{{ $bad_stock }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <a href="{{ route('employee.oldStockProduct', ['status' => 'reuse']) }}">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fab fa-buffer"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Reuse</span>
                                <span class="info-box-number">{{ $reuse_stock }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                </div>

            </div>
        </div>
    </div>
@endif




@if ($employee->team_admin)
    <div class="row">


        <div class="col-lg-4 col-12">
            <div class="small-box bg-info">
                <div class="inner text-center">
                    <h3>{{ $myCustomer }}</h3>
                    <p>My Total Customers</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('employee.myCustomers', $employee) }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
@endif

