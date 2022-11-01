<div class="table-responsive">
    <table class="table table-bordered table-sm text-nowrap">
        <thead>
            <tr>
                <th>SL</th>
                <th>Action</th>
                <th>Status</th>
                <th>Party_name</th>
                <th>Total Product</th>
                <th>Stocked Out</th>
                <th>Current Stock</th>
                <th>Req Product Price</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requisitions as $rq)
                <tr>
                    <td>{{$loop->index +1}}</td>
                    <td><a href="" class="btn btn-success btn-sm">Details</a></td>
                    <td>{{$rq->status}}</td>
                    <td>{{$rq->party_name}}</td>
                    <td>{{$rq->withStockRequisitionProducts->count()}}</td>
                    <td>{{$rq->stockedOutRequisitionProducts}}</td>
                    <td>{{$rq->withStockRequisitionProducts->count() - $rq->stockedOutRequisitionProducts}}</td>
                    <td>{{$rq->req_product_final_price}}</td>
                </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-danger h3"></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{$requisitions->render()}}
