@extends('admin.layouts.adminMaster')
@push('title')
    Emplyee Dashboard |Visits
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> {{ ucfirst($type) }} Convayances Bill </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsve">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>ID</th>
                        <th>Action</th>
                        <th>Visit</th>
                        <th>Customer</th>
                        <th>Employee</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        @forelse ($convayance_bills as $cb)
                            <tr>
                                <td>{{ $cb->id }}</td>
                                <td><a class="btn btn-success btn-sm"
                                        href="{{ route('admin.convayancesDetails', $cb) }}">Details</a></td>
                                <td><a
                                        href="{{ route('admin.customerVisitview', ['visit' => $cb->visit_id, 'visit_plan' => $cb->visit_plan_id]) }}">{{ $cb->visit_id }}</a>
                                </td>
                                <td>{{ $cb->customer ? $cb->customer->customer_name : '' }}</td>
                                <td>{{ $cb->employee ? $cb->employee->name : '' }}</td>
                                <td>{{ $cb->total_amount }}</td>
                                <td>
                                    @if ($cb->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif ($cb->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ($cb->status == 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No Covayances Bill Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
