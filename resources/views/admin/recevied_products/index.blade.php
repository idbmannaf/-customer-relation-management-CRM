@extends('admin.layouts.adminMaster')
@push('title')
    Ready To Receive Requisition Products
@endpush

@section('content')
<div class="card">
    <div class="card-header">Ready To Receive Requisition Products of {{$type}}</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Action</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Requisition Type</th>
                        <th>Party_name</th>
                        <th>Req Product Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requisitions as $rq)

                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td><a href="{{route('admin.readyToReceiveProductDetails',['requisition'=>$rq,'type'=>$type])}}" class="btn btn-success btn-sm">Details</a></td>
                        <td>{{ $rq->date }}</td>
                        <td>
                            @if ($rq->done_at)
                                <span class="badge badge-success">Done</span>
                            @elseif ($rq->send_to_receive_by)
                                <span class="badge badge-info">Recevied</span>
                            @elseif ($rq->status == 'approved')
                                <span class="badge badge-warning">Approved</span>
                            @elseif ($rq->status == 'pending')
                                <span class="badge badge-danger">Pending</span>
                            @endif

                        </td>
                        <td>{{ $rq->type }}</td>
                        <td>{{ $rq->party_name }}</td>
                        <td>{{ $rq->req_product_total_price }}</td>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-danger h3"></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
