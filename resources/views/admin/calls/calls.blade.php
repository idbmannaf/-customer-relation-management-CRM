@extends('admin.layouts.adminMaster')
@push('title')
    Asign Call/Task/Complain
@endpush

@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">{{ ucfirst($type) }} Service Call
                @can('service-call-add')
                    <a href="{{ route('admin.addCalls') }}" class="btn btn-danger">New Service Call</a>
                @endcan

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
                            <th>Status</th>
                            <th>Employee</th>
                            <th>Customer</th>
                            <th>Customer Location</th>
                            <th>Purpose of visit</th>
                            <th>Admin Note</th>
                            <th>Called By</th>
                            <th>Approved At</th>
                            <th>Approved By</th>
                            <th>Reffer Team Head</th>
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
                                            @can('service-call-update')
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.updateCalls', $call) }}">Details</a>
                                            @endcan

                                            @if ($call->inhouse_product)
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.sendRequestToTheCustomer', $call) }}">Send
                                                    Request To The Customer
                                                </a>

                                                @if (count($call->call_products) && !$call->not_received_product())
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.callWiseVisitPlan', ['call' => $call, 'inhouse' => true]) }}">Visit
                                                        Plans</a>
                                                @endif
                                            @else
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.callWiseVisitPlan', $call) }}">Visit
                                                    Plans</a>
                                            @endif



                                        </div>
                                </td>
                                <td>
                                    @if ($call->done_at)
                                        <div class="badge badge-success">Done</div>
                                    @elseif($call->approved_at)
                                        <div class="badge badge-warning">Approved</div>
                                    @else
                                        <div class="badge badge-danger">Pending</div>
                                    @endif
                                </td>
                                <td>{{ $call->employee ? $call->employee->name : 'No Employee Selected' }}</td>
                                <td>
                                    @if ($call->customer)
                                        {{ $call->customer->customer_name }} ({{ $call->customer->customer_code }})
                                    @endif
                                </td>
                                <td>{{ $call->customer_office ? $call->customer_office->title : $call->customer_address }}
                                </td>
                                <td>{{ $call->purpose_of_visit }}</td>
                                <td>{{ $call->admin_note }}</td>
                                <td>{{ $call->addedBy
                                    ? ($call->addedBy->employee
                                        ? 'Employee:' . $call->addedBy->employee->name ."(".$call->addedBy->employee->employee_id .")"
                                        : ($call->addedBy->customer
                                            ? 'Customer:' . ( $call->addedBy->customer->customer_name.": ".$call->addedBy->customer->customer_code)
                                            : $call->addedBy->name))
                                    : '' }}
                                </td>
                                <td>{{ $call->approved_at }}</td>
                                {{-- <td>{{$call->approvedBy}}</td> --}}
                                <td>{{ $call->approvedBy
                                    ? ($call->approvedBy->employee
                                        ? 'Employee:' . $call->approvedBy->employee->name ."(".$call->approvedBy->employee->employee_id .")"
                                        : ($call->approvedBy->customer
                                            ? 'Customer:' . ( $call->approvedBy->customer->customer_name.": ".$call->approvedBy->customer->customer_code)
                                            : $call->approvedBy->name))
                                    : '' }}
                                </td>
                                <td>
                                    @foreach ($call->refferTeamHeads as $rfTh)
                                        <small>{{ $rfTh->name }}</small>,<br>
                                    @endforeach
                                </td>
                            </tr>
                            {{-- modal Start --}}

                            {{-- modal End --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection



@push('js')
@endpush
