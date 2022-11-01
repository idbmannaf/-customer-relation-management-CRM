@extends('employee.layouts.employeeMaster')
@push('title')
    Employee Dashboard | Attendance Report
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
                            <td>Product Type</td>
                            <td>Product</td>
                            <td>Quantity</td>
                            <td>Customer</td>
                            <td>Call</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $item)
                            <tr>
                                <td>{{ $item->id }}</td>

                                <td>

                                    @if (!$item->received)
                                        <a href="{{ route('employee.receivedCustomerRequestProductitem', ['item'=>$item,'status'=>'received']) }}"
                                            class="btn btn-sm btn-danger">Received</a>
                                    @elseif ($item->call->done_by && !$item->delivered)
                                        <a href="{{ route('employee.receivedCustomerRequestProductitem',['item'=>$item,'status'=>'delivered']) }}"
                                            class="btn btn-sm btn-danger">Delivered</a>
                                    {{-- @elseif (!$item->customer_received)
                                        <a href="{{ route('employee.receivedCustomerRequestProductitem', customer_received) }}"
                                            class="btn btn-sm btn-danger">Delivered</a> --}}
                                    @endif


                                </td>
                                <td>
                                    @if ($item->customer_received)
                                        <span class="badge badge-success">Customer Received</span>
                                    @elseif ($item->delivered)
                                        <span class="badge badge-warning">Delivered</span>
                                    @elseif ($item->received)
                                        <span class="badge badge-secondary">Received</span>
                                    @elseif ($item->sent)
                                        <span class="badge badge-info">Sent</span>
                                        @else
                                        <span class="badge badge-danger">Customer Not Sent</span>
                                    @endif
                                </td>

                                <td>{{ $item->product ? $item->product->product_type : '' }}</td>
                                <td>{{ $item->product ? $item->product->name : '' }}</td>
                                <td>
                                    {{ $item->quantity }}
                                </td>
                                <td>
                                    @if ($item->customer)
                                        {{ $item->customer->company_name }} ({{ $item->customer->customer_code }})
                                    @endif
                                </td>
                                <td>{{ $item->call_id }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $products->render() }}
        </div>
    </div>
@endsection
