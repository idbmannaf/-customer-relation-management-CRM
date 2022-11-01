@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Visits
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">ConvayancesBill </div>
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
                        <th>Payment Status</th>

                    </thead>
                    <tbody>
                        @forelse ($convayance_bills as $cb)
                            <tr>
                                <td>{{ $cb->id }}</td>
                                <td>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('employee.convayancesBillPaymentDetails', $cb) }}">{{$cb->paid  ? 'Details' : 'Details for
                                        Payment'}}</a>

                                    </td>
                                <td><a
                                        href="{{ route('employee.customerVisitview', ['visit' => $cb->visit_id, 'visit_plan' => $cb->visit_plan_id]) }}">{{ $cb->visit_id }}</a>
                                </td>
                                <td>{{ $cb->customer ? $cb->customer->customer_name : '' }}</td>
                                <td>{{ $cb->employee ? $cb->employee->name : '' }}</td>
                                <td>{{ $cb->total_amount }}</td>
                                <td>
                                    @if ($cb->paid)
                                        <span class="badge badge-success">Paid</span>
                                    @else
                                        <span class="badge badge-danger">Unpaid</span>
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
