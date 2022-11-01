@extends('customer.layouts.customerMaster')
@push('title')
    Transaction History
@endpush
@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            {{ ucfirst($type) }} Products In House
        </div>

        <div class="card-body" id="printArea">
            @include('alerts.alerts')
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Action</td>
                            <td>Status</td>
                            <td>Product</td>
                            <td>Quantity</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $item)
                            <tr>
                                <td>{{ $item->id }}</td>

                                <td>

                                    @if (!$item->sent)
                                        <a href="{{ route('customer.sendTheProductItemInhouse', ['item' => $item, 'status' => 'sent']) }}"
                                            class="btn btn-sm btn-danger">Sent</a>
                                    @elseif ($item->delivered && !$item->customer_received)
                                        <a href="{{ route('customer.sendTheProductItemInhouse', ['item' => $item, 'status' => 'received']) }}"
                                            class="btn btn-sm btn-danger">Received</a>

                                    @endif



                                </td>
                                <td>

                                    @if ($item->customer_received)
                                        <span class="badge badge-success">Received By You</span>
                                    @elseif ($item->delivered)
                                        <span class="badge badge-warning">Delivered</span>
                                    @elseif ($item->received)
                                        <span class="badge badge-secondary">Received By Team Head</span>
                                    @elseif ($item->sent)
                                        <span class="badge badge-danger">Sent</span>
                                    @endif

                                </td>
                                <td>{{ $item->product ? $item->product->name : '' }}</td>
                                <td>
                                    {{ $item->quantity }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $products->render() }}
        </div>
    </div>
@endsection
