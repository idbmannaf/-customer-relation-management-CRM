<div class="card">
    <div class="card-header">Service Call</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('employee.calls', ['type' => 'pending']) }}">

                    <div><i class="fas fa-mobile-alt fa-2x text-danger"></i></div>
                    <div>Pending</div>
                    <div>{{ $pending_calls }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.calls', ['type' => 'approved']) }}">
                    <div><i class="fas fa-mobile-alt fa-2x text-warning"></i></div>
                    <div>Approved</div>
                    <div>{{ $approved_calls }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.calls', ['type' => 'done']) }}">
                    <div><i class="fas fa-mobile-alt fa-2x text-success"></i></div>
                    <div>Done</div>
                    <div>{{ $done_calls }}</div>
                </a>
            </div>
            @if (auth()->user()->employee->team_admin)
                <div class="col-4 text-center">
                    <a href="{{ route('employee.referanceCall') }}">
                        <div><i class="fas fa-traffic-light fa-2x text-success"></i></div>
                        <div>Referance</div>
                        <div>{{ $reff_calls }}</div>
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>
{{-- Visit Plan  --}}
<div class="card">
    <div class="card-header">Visit Plan</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('employee.customerVisit.index', ['status' => 'pending']) }}">
                    <div><i class="fas fa-paper-plane fa-2x text-danger"></i></div>
                    <div>Pending</div>
                    <div>{{ $pending_visit_plan }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.customerVisit.index', ['status' => 'approved']) }}">
                    <div><i class="fas fa-paper-plane fa-2x text-warning"></i></div>
                    <div>Approved</div>
                    <div>{{ $approved_visit_plan }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.customerVisit.index', ['status' => 'completed']) }}">
                    <div><i class="fas fa-paper-plane fa-2x text-success"></i></div>
                    <div>Completed</div>
                    <div>{{ $completed_visit_plan }}</div>
                </a>
            </div>


        </div>
    </div>
</div>
{{-- Visit  --}}
<div class="card">
    <div class="card-header">Visits</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('employee.allOfMyTeamMemberVisits', ['status' => 'pending']) }}">
                    <div><i class="fas fa-street-view fa-2x text-danger"></i></div>
                    <div>Pending</div>
                    <div>{{ $total_pending_visit }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.allOfMyTeamMemberVisits', ['status' => 'approved']) }}">
                    <div><i class="fas fa-street-view fa-2x text-warning"></i></div>
                    <div>Approved</div>
                    <div>{{ $total_approved_visit }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.allOfMyTeamMemberVisits', ['status' => 'completed']) }}">
                    <div><i class="fas fa-street-view fa-2x text-success"></i></div>
                    <div>Completed</div>
                    <div>{{ $total_completed_visit }}</div>
                </a>
            </div>


        </div>
    </div>
</div>
{{-- Customer Offer Quotation --}}
<div class="card">
    <div class="card-header">Customer Offer Quotation</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('employee.myCustomerOffers', ['type' => 'pending']) }}">
                    <div><i class="fab fa-buffer fa-2x text-secondary"></i></div>
                    <div>Pending</div>
                    <div>{{ $pending_offer_quot }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.myCustomerOffers', ['type' => 'approved']) }}">
                    <div><i class="fab fa-buffer fa-2x text-success"></i></div>
                    <div>Approved</div>
                    <div>{{ $approved_offer_quot }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.myCustomerOffers', ['type' => 'rejected']) }}">
                    <div><i class="fab fa-buffer fa-2x text-danger"></i></div>
                    <div>Rejected</div>
                    <div>{{ $rejected_offer_quot }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.myCustomerOffers', ['type' => 'customer_approved']) }}">
                    <div><i class="fab fa-buffer fa-2x text-success"></i></div>
                    <div>Customer Approved</div>
                    <div>{{ $customer_approved_offer_quot }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.myCustomerOffers', ['type' => 'customer_not_approved']) }}">
                    <div><i class="fab fa-buffer fa-2x text-warning"></i></div>
                    <div>Customer Not Approved</div>
                    <div>{{ $customer_not_approved_offer_quot }}</div>
                </a>
            </div>


        </div>
    </div>
</div>
{{-- Product Requisition --}}
<div class="card">
    <div class="card-header">Product Requisition</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('employee.requisitionIndex', ['type' => 'product', 'status' => 'pending']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-danger"></i></div>
                    <div>Pending</div>
                    <div>{{ $pending_product_rq }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.requisitionIndex', ['type' => 'product', 'status' => 'reviewed']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-warning"></i></div>
                    <div>Approved</div>
                    <div>{{ $reviewed_product_rq }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                @if ($employee->team_admin && $employee->company->inventory_maintain_permission)
                    <a href="{{ route('employee.inventoryMaintain', ['type' => 'product']) }}">
                    @else
                        <a
                            href="{{ route('employee.requisitionIndex', ['type' => 'product', 'status' => 'approved']) }}">
                @endif
                <div><i class="fab fa-product-hunt fa-2x text-success"></i></div>
                <div>Approved</div>
                <div>{{ $approved_product_rq }}</div>
                </a>
            </div>


        </div>
    </div>
</div>
{{-- Spare Parts Requisition --}}
<div class="card">
    <div class="card-header">Spare Parts Requisition</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('employee.allOfMyTeamMemberVisits', ['status' => 'approved']) }}">
                    <div><i class="fas fa-cogs fa-2x text-approved"></i></div>
                    <div>Pending</div>
                    <div>{{ $pending_sp_rq }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.requisitionIndex', ['type' => 'spear_parts', 'status' => 'reviewed']) }}">
                    <div><i class="fas fa-cogs fa-2x text-warning"></i></div>
                    <div>Approved</div>
                    <div>{{ $reviewed_sp_rq }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                @if ($employee->team_admin && $employee->company->inventory_maintain_permission)
                    <a href="{{ route('employee.inventoryMaintain', ['type' => 'spear_parts']) }}">
                    @else
                        <a
                            href="{{ route('employee.requisitionIndex', ['type' => 'spear_parts', 'status' => 'approved']) }}">
                @endif
                <div><i class="fas fa-cogs fa-2x text-success"></i></div>
                <div>Approved</div>
                <div>{{ $approved_sp_rq }}</div>
                </a>
            </div>


        </div>
    </div>
</div>
{{-- Challan And Invoice --}}
<div class="card">
    <div class="card-header">Challan And Invoice</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('employee.chalanAndInvoice', ['type' => 'challan']) }}">
                    <div><i class="fas fa-users fa-2x text-success"></i></div>
                    <div>Challan</div>
                    <div>{{ $total_challan }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.chalanAndInvoice', ['type' => 'invoice']) }}">
                    <div><i class="fas fa-users fa-2x text-success"></i></div>
                    <div>Invoice</div>
                    <div>{{ $total_invoice }}</div>
                </a>
            </div>

        </div>
    </div>
</div>
{{-- Ready To Received Product/Spare Parts --}}
<div class="card">
    <div class="card-header">Ready To Received Product/Spare Parts</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('employee.readyToReceiveProduct', ['type' => 'spear_parts']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-success"></i></div>
                    <div>Spare Parts</div>
                    <div>{{ $r_to_r_sp }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('employee.readyToReceiveProduct', ['type' => 'product']) }}">
                    <div><i class="fas fa-cogs fa-2x text-success"></i></div>
                    <div>Products</div>
                    <div>{{ $r_to_r_p }}</div>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Unused Product & Spare Parts --}}
@if (!auth()->user()->employee->company->access_all_call_visit_plan_without_call ||
    !auth()->user()->employee->team_admin_id)
    <div class="card">
        <div class="card-header">Unused Product & Spare Parts</div>
        <div class="card-body">
            <div class="row">
                <div class="col-4 text-center">
                    <a href="{{ route('employee.unUsedProduct', ['type' => 'spear_parts ']) }}">
                        <div><i class="fab fa-buffer fa-2x text-secondary"></i></div>
                        <div>Spare Parts</div>
                        <div>{{ $unused_spear_parts }}</div>
                    </a>
                </div>
                <div class="col-4 text-center">
                    <a href="{{ route('employee.unUsedProduct', ['type' => 'product ']) }}">
                        <div><i class="fab fa-buffer fa-2x text-success"></i></div>
                        <div>Product</div>
                        <div>{{ $unused_products }}</div>
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
                <div class="col-4 text-center">
                    <a href="{{ route('employee.receiveUnusedProducts', ['type' => 'spear_parts']) }}">
                        <div><i class="fab fa-buffer fa-2x text-info"></i></div>
                        <div>Spare Parts</div>
                        <div>{{ $receiveUnusedspear_parts }}</div>
                    </a>
                </div>
                <div class="col-4 text-center">
                    <a href="{{ route('employee.receiveUnusedProducts', ['type' => 'product']) }}">
                        <div><i class="fab fa-buffer fa-2x text-success"></i></div>
                        <div>Product</div>
                        <div>{{ $receiveUnusedProduct }}</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Danmaged Product Assign --}}
@if (auth()->user()->employee->team_admin &&
    auth()->user()->employee->company->store_damage_product_assign_permission)
    <div class="card">
        <div class="card-header">Danmaged Product Assign</div>
        <div class="card-body">
            <div class="row">
                <div class="col-4 text-center">
                    <a href="{{ route('employee.showReceivedProductInServiceTeamHead', ['type' => 'spear_parts']) }}">
                        <div><i class="fab fa-buffer fa-2x text-info"></i></div>
                        <div>Spare Parts</div>
                        <div>{{ $receivedSpearPpartFromUnused }}</div>
                    </a>
                </div>
                <div class="col-4 text-center">
                    <a href="{{ route('employee.showReceivedProductInServiceTeamHead', ['type' => 'product']) }}">
                        <div><i class="fab fa-buffer fa-2x text-success"></i></div>
                        <div>Product</div>
                        <div>{{ $receivedProductFromUnused }}</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

