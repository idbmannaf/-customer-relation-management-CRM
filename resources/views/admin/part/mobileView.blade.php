<div class="card">
    <div class="card-header">Attandance Today</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.attendance', 'present') }}">
                    <div><i class="fas fa-users fa-2x text-success"></i></div>
                    <div>Present</div>
                    <div>{{ $total_present }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.attendance', 'absent') }}">
                    <div><i class="fas fa-users fa-2x text-danger"></i></div>
                    <div>Absent</div>
                    <div>{{ $total_absent }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.attendance', 'late_entry') }}">
                    <div><i class="fas fa-users fa-2x text-warning"></i></div>
                    <div>Late</div>
                    <div>{{ $total_late }}</div>
                </a>
            </div>

        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">Service Call</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.calls', ['type' => 'pending']) }}">
                    <div><i class="fas fa-cogs fa-2x text-danger"></i></div>
                    <div>Pending</div>
                    <div>{{ $total_pending_call }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.calls', ['type' => 'approved']) }}">
                    <div><i class="fas fa-cogs fa-2x text-warning"></i></div>
                    <div>Approved</div>
                    <div>{{ $total_approved_call }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.calls', ['type' => 'done']) }}">
                    <div><i class="fas fa-cogs fa-2x text-success"></i></div>
                    <div>Done</div>
                    <div>{{ $total_done_call }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.refferedCall') }}">
                    <div><i class="fas fa-cogs fa-2x text-success"></i></div>
                    <div>Referance</div>
                    <div>{{ $reff_calls }}</div>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Visit Plan --}}
<div class="card">
    <div class="card-header">Visit Plan ({{ $total_visit_plan }})</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.visitPlans', ['status' => 'pending']) }}">
                    <div><i class="fas fa-paper-plane fa-2x text-danger"></i></div>
                    <div>Pending</div>
                    <div>{{ $pending_visit_plan }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.visitPlans', ['status' => 'approved']) }}">
                    <div><i class="fas fa-paper-plane fa-2x text-warning"></i></div>
                    <div>Approved</div>
                    <div>{{ $approved_visit_plan }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.visitPlans', ['status' => 'completed']) }}">
                    <div><i class="fas fa-paper-plane fa-2x text-success"></i></div>
                    <div>Completed</div>
                    <div>{{ $completed_visit_plan }}</div>
                </a>
            </div>


        </div>
    </div>
</div>

{{-- Visits --}}
<div class="card">
    <div class="card-header">Visits (({{ $total_visit }}))</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.allVisits', ['type' => 'all']) }}">
                    <div><i class="fas fa-street-view fa-2x text-secondary"></i></div>
                    <div>Today</div>
                    <div>{{ $total_today_visit }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.allVisits', ['type' => 'pending']) }}">
                    <div><i class="fas fa-street-view fa-2x text-danger"></i></div>
                    <div>Pending</div>
                    <div>{{ $total_pending_visit }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.allVisits', ['type' => 'approved']) }}">
                    <div><i class="fas fa-street-view fa-2x text-warning"></i></div>
                    <div>Approved</div>
                    <div>{{ $total_approved_visit }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.allVisits', ['type' => 'completed']) }}">
                    <div><i class="fas fa-street-view fa-2x text-success"></i></div>
                    <div>Completed</div>
                    <div>{{ $total_completed_visit }}</div>
                </a>
            </div>


        </div>
    </div>
</div>

{{-- Customer Quotation --}}
<div class="card">
    <div class="card-header">Customer Offer Quotation</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.customerOffers', ['type' => 'all']) }}">
                    <div><i class="fas fa-hand-holding-usd fa-2x text-warning"></i></div>
                    <div>Total</div>
                    <div>{{ $total_quatation }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.customerOffers', ['type' => 'pending']) }}">
                    <div><i class="fas fa-hand-holding-usd fa-2x text-danger"></i></div>
                    <div>Pending</div>
                    <div>{{ $pending_quatation }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.customerOffers', ['type' => 'approved']) }}">
                    <div><i class="fas fa-hand-holding-usd fa-2x text-danger"></i></div>
                    <div>Approved</div>
                    <div>{{ $approved_quatation }}</div>
                </a>
            </div>
            <div class="col-4 pt-2 text-center">
                <a href="{{ route('admin.customerOffers', ['type' => 'customer_approved']) }}">
                    <div><i class="fas fa-hand-holding-usd fa-2x text-success"></i></div>
                    <div>Customer Approved</div>
                    <div>{{ $customer_approved_quatation }}</div>
                </a>
            </div>
            <div class="col-4 pt-2 text-center">
                <a href="{{ route('admin.customerOffers', ['type' => 'customer_not_approved']) }}">
                    <div><i class="fas fa-hand-holding-usd fa-2x text-success"></i></div>
                    <div>Customer Not Approved</div>
                    <div>{{ $customer_not_approved_quatation }}</div>
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
                <a href="{{ route('admin.requisitions', ['type' => 'product', 'status' => 'pending']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-danger"></i></div>
                    <div>Pending</div>
                    <div>{{ $pending_product_rq }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.requisitions', ['type' => 'product', 'status' => 'reviewed']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-wrning"></i></div>
                    <div>Reviewed</div>
                    <div>{{ $reviewed_product_rq }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.requisitions', ['type' => 'product', 'status' => 'approved']) }}">
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
                <a href="{{ route('admin.requisitions', ['type' => 'spear_parts', 'status' => 'pending']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-danger"></i></div>
                    <div>Pending</div>
                    <div>{{ $pending_sp_rq }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.requisitions', ['type' => 'spear_parts', 'status' => 'reviewed']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-wrning"></i></div>
                    <div>Reviewed</div>
                    <div>{{ $reviewed_sp_rq }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.requisitions', ['type' => 'spear_parts', 'status' => 'approved']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-success"></i></div>
                    <div>Approved</div>
                    <div>{{ $approved_sp_rq }}</div>
                </a>
            </div>

        </div>
    </div>
</div>
{{-- Challan & Invoice --}}
<div class="card">
    <div class="card-header">Challan & Invoice </div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.chalanAndInvoice', ['type' => 'challan']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-danger"></i></div>
                    <div>Total Chalan</div>
                    <div>{{ $total_challan }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.chalanAndInvoice', ['type' => 'invoice']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-wrning"></i></div>
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
                <a href="{{ route('admin.readyToReceiveProduct', ['type' => 'spear_parts']) }}">
                    <div><i class="fab fa-product-hunt fa-2x text-success"></i></div>
                    <div>Spare Parts</div>
                    <div>{{ $r_to_r_sp }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.readyToReceiveProduct', ['type' => 'product']) }}">
                    <div><i class="fas fa-cogs fa-2x text-success"></i></div>
                    <div>Products</div>
                    <div>{{ $r_to_r_p }}</div>
                </a>
            </div>

        </div>
    </div>
</div>
{{-- Unused Product & Spare Parts --}}
<div class="card">
    <div class="card-header">Unused Product & Spare Parts</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.unUsedProduct', ['type' => 'spear_parts ']) }}">
                    <div><i class="fab fa-buffer fa-2x text-secondary"></i></div>
                    <div>Spare Parts</div>
                    <div>{{ $unused_spear_parts }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.unUsedProduct', ['type' => 'product ']) }}">
                    <div><i class="fab fa-buffer fa-2x text-success"></i></div>
                    <div>Product</div>
                    <div>{{ $unused_products }}</div>
                </a>
            </div>
        </div>
    </div>
</div>
{{-- Received Products --}}
<div class="card">
    <div class="card-header">Received Products</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.inventoryMaintain', ['type' => 'spear_parts ']) }}">
                    <div><i class="fab fa-buffer fa-2x text-secondary"></i></div>
                    <div>Spare Parts</div>
                    <div>{{ $received_spare_part_req }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.inventoryMaintain', ['type' => 'product ']) }}">
                    <div><i class="fab fa-buffer fa-2x text-success"></i></div>
                    <div>Product</div>
                    <div>{{ $received_product_req }}</div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Received Unused Product & Spare Parts --}}
<div class="card">
    <div class="card-header">Received Unused Product & Spare Parts</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.receiveUnusedProducts', ['type' => 'spear_parts']) }}">
                    <div><i class="fab fa-buffer fa-2x text-info"></i></div>
                    <div>Spare Parts</div>
                    <div>{{ $receiveUnusedspear_parts }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.receiveUnusedProducts', ['type' => 'product']) }}">
                    <div><i class="fab fa-buffer fa-2x text-success"></i></div>
                    <div>Product</div>
                    <div>{{ $receiveUnusedProduct }}</div>
                </a>
            </div>
        </div>
    </div>
</div>
{{-- Danmaged Product Assign --}}
<div class="card">
    <div class="card-header">Danmaged Product Assign</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.showReceivedProductInServiceTeamHead', ['type' => 'spear_parts']) }}">
                    <div><i class="fab fa-buffer fa-2x text-info"></i></div>
                    <div>Spare Parts</div>
                    <div>{{ $receivedSpearPpartFromUnused }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.showReceivedProductInServiceTeamHead', ['type' => 'product']) }}">
                    <div><i class="fab fa-buffer fa-2x text-success"></i></div>
                    <div>Product</div>
                    <div>{{ $receivedProductFromUnused }}</div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Old Stock --}}
<div class="card">
    <div class="card-header">Old Stock</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.oldStockProduct', ['status' => 'repair']) }}">
                    <div><i class="fab fa-buffer fa-2x text-info"></i></div>
                    <div>Repeir</div>
                    <div>{{ $repair_stock }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.oldStockProduct', ['status' => 'recharge']) }}">
                    <div><i class="fab fa-buffer fa-2x text-secondary"></i></div>
                    <div>Recharge</div>
                    <div>{{ $recharge_stock }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.oldStockProduct', ['status' => 'bad']) }}">
                    <div><i class="fab fa-buffer fa-2x text-danger"></i></div>
                    <div>Bad</div>
                    <div>{{ $bad_stock }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.oldStockProduct', ['status' => 'reuse']) }}">
                    <div><i class="fab fa-buffer fa-2x text-success"></i></div>
                    <div>Reuse</div>
                    <div>{{ $reuse_stock }}</div>
                </a>
            </div>
        </div>
    </div>
</div>






<div class="card">
    <div class="card-body">
        <h4 class="text-success">More</h4>
        <div class="row">
            <div class="col-4 text-center">
                <a href="{{ route('admin.product.index', ['service_type' => 'products']) }}">
                    <div><i class="fas fab fa-product-hunt fa-2x text-success"></i></div>
                    <div>Total Products</div>
                    <div>{{ $total_products }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.product.index',['service_type'=>'spare_parts']) }}">
                    <div><i class="fas fa-cog fa-2x text-success"></i></div>
                    <div>Total Spare Parts</div>
                    <div>{{ $total_spare_parts }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.company.index') }}">
                    <div><i class="fas fa-building fa-2x text-success"></i></div>
                    <div>Total Company</div>
                    <div>{{ $total_company }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.employee.index') }}">
                    <div><i class="fas fa-user-cog fa-2x text-danger"></i></div>
                    <div>Total Employee</div>
                    <div>{{ $total_employee }}</div>
                </a>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('admin.customer.index') }}">
                    <div><i class="fas fa-user-secret fa-2x text-warning"></i></div>
                    <div>Total Customer</div>
                    <div>{{ $total_customer }}</div>
                </a>
            </div>

        </div>
    </div>
</div>
