@extends('admin.layouts.adminMaster')
@push('title')
   Receive Products
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-success">Received  {{ $type }} <a href="{{route('admin.addReceiveProductForStockManage')}}" class="btn btn-danger btn-sm">New Received {{ $type }}</a></div>
        <div class="card-body">
            @include('alerts.alerts')
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($receivedProducts as $rp)
                        <tr>
                            <td>{{$loop->index + 1}}</td>
                            <td>{{$rp->created_at}}</td>
                            <td>
                                @if ($rp->received)
                                <span class="text-success">Received</span>
                                @else
                                <span class="text-danger">Not Received Yet</span>
                                @endif
                            </td>
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
