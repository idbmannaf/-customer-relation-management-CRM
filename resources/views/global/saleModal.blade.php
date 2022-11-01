<div class="modal fade" id="vs-{{ $visit->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sales Of Visit Plan: {{$visit_plan->id}} and Visit: {{ $visit->id }} </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @foreach ($visit->sales_items as $v_sale)
                <div class="card">
                    <ul class="list-group">
                        <li class="list-group-item"><b>Product Id: </b>{{ $v_sale->product_id }}</li>
                        <li class="list-group-item"><b>Product Name: </b>{{ $v_sale->product_name }}</li>
                        <li class="list-group-item"><b>Warranty: </b>{{ $v_sale->product_warranty }}</li>
                        <li class="list-group-item"><b>Capacity: </b>{{ $v_sale->product_capacity }}</li>
                        <li class="list-group-item"><b>Backup Time: </b>{{ $v_sale->product_backup_time }}</li>
                        <li class="list-group-item"><b>Quantity: </b>{{ $v_sale->product_quantity }}</li>
                        <li class="list-group-item"><b>Unit Price: </b>{{ $v_sale->product_unit_price }}</li>
                        <li class="list-group-item"><b>Total Price: </b>{{ $v_sale->product_final_price }}</li>
                    </ul>
                </div>
                @endforeach


            </div>

        </div>
    </div>
</div>
