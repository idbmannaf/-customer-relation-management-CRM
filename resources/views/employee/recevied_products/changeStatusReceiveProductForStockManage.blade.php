@extends('employee.layouts.employeeMaster')
@push('title')
   Receive Products
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-success">Received  {{ $type }} <a href="{{route('employee.addReceiveProductForStockManage')}}" class="btn btn-danger btn-sm">New Received {{ $type }}</a></div>
        <div class="card-body">
            @include('alerts.alerts')
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Action</th>
                            <th>Date</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($receivedProducts as $rp)
                        <tr>
                            <td>{{$loop->index + 1}}</td>
                            <td>
                                @if ($rp->received)
                                <span class="text-success">Approved</span>
                                @else
                                <a href="{{route('employee.changeStatusReceiveProductForStockManage',['item'=>$rp])}}" class="btn btn-danger">Approve</a>
                                @endif
                            </td>
                            <td>{{$rp->created_at}}</td>
                            <td>{{$rp->product_name}}</td>
                            <td>{{$rp->quantity}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-danger text-center h4">No Received {{ $type }} Found</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
            {{$receivedProducts->render()}}

        </div>
    </div>
@endsection
