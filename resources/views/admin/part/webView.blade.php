<div class="card">
    <div class="card-body">
        <h4>Attendance Today</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.attendance', 'present') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Present</span>
                            <span class="info-box-number">{{ $total_present }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.attendance', 'absent') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Absent</span>
                            <span class="info-box-number">{{ $total_absent }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.attendance', 'late_entry') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Late</span>
                            <span class="info-box-number">{{ $total_late }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4>Service Call</h4>
        <div class="row">

            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.calls', ['type' => 'pending']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending Call</span>
                            <span class="info-box-number">{{ $total_pending_call }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.calls', ['type' => 'approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Approved Call</span>
                            <span class="info-box-number">{{ $total_approved_call }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.calls', ['type' => 'done']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Done Call</span>
                            <span class="info-box-number">{{ $total_done_call }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.refferedCall') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-file-invoice"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Referance</span>
                            <span class="info-box-number">{{ $reff_calls }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
{{-- //Visit Plan --}}
<div class="card">
    <div class="card-body">
        <h4>Visit Plan ({{ $total_visit_plan }})</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.visitPlans', ['status' => 'pending']) }}">
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
                <a href="{{ route('admin.visitPlans', ['status' => 'approved']) }}">
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
                <a href="{{ route('admin.visitPlans', ['status' => 'completed']) }}">
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
        <h4>Visits ({{ $total_visit }})</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.allVisits', ['type' => 'today']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Today</span>
                            <span class="info-box-number">{{ $total_today_visit }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.allVisits', ['type' => 'pending']) }}">
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
                <a href="{{ route('admin.allVisits', ['type' => 'approved']) }}">
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
                <a href="{{ route('admin.allVisits', ['type' => 'completed']) }}">
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
{{-- Customer Quotation --}}
<div class="card">
    <div class="card-body">
        <h4>Customer Offer Quotation</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.customerOffers', ['type' => 'all']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Quotation</span>
                            <span class="info-box-number">{{ $total_quatation }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.customerOffers', ['type' => 'pending']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending Quotation</span>
                            <span class="info-box-number">{{ $pending_quatation }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.customerOffers', ['type' => 'approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Approved Quotation</span>
                            <span class="info-box-number">{{ $approved_quatation }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.customerOffers', ['type' => 'customer_approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Customer Approved Quotation</span>
                            <span class="info-box-number">{{ $customer_approved_quatation }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.customerOffers', ['type' => 'customer_not_approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Customer Not Approved Quotation</span>
                            <span class="info-box-number">{{ $customer_not_approved_quatation }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- //Product Requistition --}}
<div class="card">
    <div class="card-body">
        <h4>Product Requisition</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.requisitions', ['type' => 'product', 'status' => 'pending']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending Requisition</span>
                            <span class="info-box-number">{{ $pending_product_rq }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.requisitions', ['type' => 'product', 'status' => 'reviewed']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Reviewed Requisition</span>
                            <span class="info-box-number">{{ $reviewed_product_rq }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.requisitions', ['type' => 'product', 'status' => 'approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-dark"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Approved Requisition</span>
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
                <a href="{{ route('admin.requisitions', ['type' => 'spear_parts', 'status' => 'pending']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending Requisition</span>
                            <span class="info-box-number">{{ $pending_sp_rq }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.requisitions', ['type' => 'spear_parts', 'status' => 'reviewed']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Reviewed Requisition</span>
                            <span class="info-box-number">{{ $reviewed_sp_rq }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.requisitions', ['type' => 'spear_parts', 'status' => 'approved']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Approved Requisition</span>
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
                <a href="{{ route('admin.chalanAndInvoice', ['type' => 'challan']) }}">
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
                <a href="{{ route('admin.chalanAndInvoice', ['type' => 'invoice']) }}">
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
                <a href="{{ route('admin.readyToReceiveProduct', ['type' => 'spear_parts']) }}">
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
                <a href="{{ route('admin.readyToReceiveProduct', ['type' => 'product']) }}">
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
<div class="card">
    <div class="card-body">
        <h4>Unused Product & Spare Parts</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.unUsedProduct', ['type' => 'spear_parts ']) }}">
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
                <a href="{{ route('admin.unUsedProduct', ['type' => 'product ']) }}">
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
{{-- Received Products --}}
<div class="card">
    <div class="card-body">
        <h4>Received Products</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.inventoryMaintain', ['type' => 'spear_parts ']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Spare Parts</span>
                            <span class="info-box-number">{{ $received_spare_part_req }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.inventoryMaintain', ['type' => 'product ']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fab fa-buffer"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Product</span>
                            <span class="info-box-number">{{ $received_product_req }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>


{{-- Received Unused Product & Spare Parts --}}
<div class="card">
    <div class="card-body">
        <h4>Received Unused Product & Spare Parts</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.receiveUnusedProducts', ['type' => 'spear_parts']) }}">
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
                <a href="{{ route('admin.receiveUnusedProducts', ['type' => 'product']) }}">
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
{{-- Danmaged Product Assign --}}
<div class="card">
    <div class="card-body">
        <h4>Danmaged Product Assign</h4>
        <div class="row">

            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.showReceivedProductInServiceTeamHead', ['type' => 'spear_parts']) }}">
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
                <a href="{{ route('admin.showReceivedProductInServiceTeamHead', ['type' => 'product']) }}">
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
{{-- Old Stock --}}
<div class="card">
    <div class="card-body">
        <h4> Old Stock</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.oldStockProduct', ['status' => 'repair']) }}">
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
                <a href="{{ route('admin.oldStockProduct', ['status' => 'recharge']) }}">
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
                <a href="{{ route('admin.oldStockProduct', ['status' => 'bad']) }}">
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
                <a href="{{ route('admin.oldStockProduct', ['status' => 'reuse']) }}">
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

{{-- More --}}
<div class="card">
    <div class="card-body">
        <h4 class="text-success">More....</h4>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.product.index', ['service_type' => 'products']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fab fa-product-hunt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Product</span>
                            <span class="info-box-number">{{ $total_products }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.product.index',['service_type'=>'spare_parts']) }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Spare Part</span>
                            <span class="info-box-number">{{ $total_spare_parts }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.company.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Company</span>
                            <span class="info-box-number">{{ $total_employee }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.employee.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Employees</span>
                            <span class="info-box-number">{{ $total_employee }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <a href="{{ route('admin.customer.index') }}">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Customers</span>
                            <span class="info-box-number">{{ $total_customer }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

