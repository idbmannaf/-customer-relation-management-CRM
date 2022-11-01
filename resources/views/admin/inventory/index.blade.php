@extends('admin.layouts.adminMaster')
@push('title')
    {{ $type }} Requisition Inventory
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            {{ $type }} Requisition For Inventory
        </div>

        @include('alerts.alerts')
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Action</th>
                        <th>Party_name</th>
                        <th>Total Product</th>
                        <th>Stocked Out</th>
                        {{-- <th>Current Not Out Stock</th> --}}
                        <th>Req Product Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requisitions as $rq)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td><a href="{{ route('admin.inventoryMaintainDetails', ['requisition' => $rq, 'type' => $type]) }}"
                                    class="btn btn-success btn-sm">Details</a></td>
                            <td>{{ $rq->party_name }}</td>
                            <td>{{ $rq->requisitionProducts->count() }}</td>
                            <td>{{ $rq->stockedOutRequisitionProductsCount() }}</td>
                            {{-- <td>{{$rq->requisitionProducts->count() - $rq->stockedOutRequisitionProductsCount()}}</td> --}}
                            <td>{{ $rq->req_product_total_price }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger h3"></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $requisitions->render() }}


    </div>
@endsection
