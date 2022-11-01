@extends('employee.layouts.employeeMaster')
@push('title')
    Ready To Collection List
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            Assing To Collection List List
        </div>

        @include('alerts.alerts')
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>#ID</th>
                        <th>Action</th>
                        <th>Customer</th>
                        <th>Invoice No</th>
                        <th>Challan No</th>
                        <th>date</th>
                        <th>Total Price</th>
                        <th>Due</th>
                        <th>Assigned To</th>
                        <th>Assigned By</th>
                        <th>Collection Status</th>
                        <th>Assigned At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($prepear_collections_from_invoces as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{$item->id}}</td>
                            <td>
                                @if (!$item->hasVisitPlan())
                                    <a href="{{ route('employee.assignToBillCollection', ['invoice' => $item]) }}"
                                        class="btn btn-success btn-sm">Assign to Collection</a>
                                @else
                                @endif
                                @if ($item->hasTransectionHistory())
                                    <a href="{{ route('employee.transectionHistory', ['invoice' => $item]) }}"
                                        class="btn btn-warning btn-sm">Transection</a>
                                @endif
                                @if ($item->hasVisitPlan())
                                    <a href="{{ route('employee.customerVisitPlansOfInvoice', ['invoice' => $item]) }}"
                                        class="btn btn-success btn-sm">Visti Plans</a>
                                @endif
                            </td>

                            <td>{{$item->customer ? $item->customer->customer_name : ''}}</td>
                            <td>{{ $item->invoice_no }}</td>
                            <td>{{ $item->challan_no }}</td>

                            <td>
                                {{ $item->invoice_date }}

                            </td>
                            <td>
                                {{ $item->total_amount }}
                            </td>
                            <td>
                                @if ($item->hasVisitPlan())
                                    {{ $item->due }}
                                @else
                                    {{ $item->due > 0 ? $item->due : $item->total_amount }}
                                @endif
                            </td>
                            <td>{{ $item->assignedTo ? $item->assignedTo->name : '' }}</td>
                            <td>{{ $item->assignedBy ? ($item->assignedBy->employee ? $item->assignedBy->employee->name : $item->assignedBy->name) : '' }}
                            </td>
                            <td>{{ $item->payment_status }}</td>
                            <td>{{ $item->assigned_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $prepear_collections_from_invoces->render() }}
    </div>
@endsection
