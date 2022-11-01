@extends('customer.layouts.customerMaster')
@push('title')
    Customer Dashboard | Attendance Report
@endpush

@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Assign Call/Task/Complain
                <a href="{{ route('customer.addCalls') }}" class="btn btn-danger"><i class="fas fa-plus"></i></a>
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>Date Time</th>
                            <th>Action</th>
                            <th>Employee</th>
                            <th>Customer</th>
                            <th>Customer Location</th>
                            <th>Purpose of visit</th>
                            <th>Admin Note</th>
                            <th>Approved At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($calls as $call)
                            <tr>
                                <td>{{ $call->date_time }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if (!$call->approved_at)
                                                <a class="dropdown-item"
                                                    href="{{ route('customer.updateCalls', $call) }}">Edit</a>
                                            @endif
                                        </div>
                                </td>
                                <td>{{ $call->employee ? $call->employee->name : 'No Employee Selected' }}</td>
                                <td>{{ $call->customer->customer_name }} ({{ $call->customer->customer_code }})</td>
                                <td>{{ $call->customer_office ? $call->customer_office->title : $call->customer_address}}</td>
                                <td>{{ $call->purpose_of_visit }}</td>
                                <td>{{ $call->admin_note }}</td>
                                <td>{{ $call->approved_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection



@push('js')
@endpush
