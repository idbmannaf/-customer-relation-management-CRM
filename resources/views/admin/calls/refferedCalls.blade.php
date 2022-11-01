@extends('admin.layouts.adminMaster')
@push('title')
    Asign Call/Task/Complain
@endpush

@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Reffered Service Call
                <a href="{{ route('admin.addCalls') }}" class="btn btn-danger">New Service Call</a>
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
                                            <a class="dropdown-item"
                                                href="{{ route('admin.updateCalls', $call) }}">Edit</a>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.callWiseVisitPlan', $call) }}">Visit Plan</a>


                                        </div>
                                </td>
                                <td>{{ $call->employee ? $call->employee->name : 'No Employee Selected' }}</td>
                                <td>{{ $call->customer->customer_name }} ({{ $call->customer->customer_code }})</td>
                                <td>
                                    @if ($call->customer_office)
                                    {{ $call->customer_office->title }}
                                    @else
                                    {{$call->customer_address}}
                                    @endif
                                    </td>
                                <td>{{ $call->purpose_of_visit }}</td>
                                <td>{{ $call->admin_note }}</td>
                                <td>{{ $call->addedBy
                                    ? ($call->addedBy->employee
                                        ? 'Employee:' . $call->addedBy->employee->employee_id
                                        : ($call->addedBy->customer
                                            ? 'Customer:' . ($call->addedBy->customer->customer_code ?? $call->addedBy->customer->customer_name)
                                            : $call->addedBy->name))
                                    : '' }}
                                </td>
                                <td>{{ $call->approved_at }}</td>
                                {{-- <td>{{$call->approvedBy}}</td> --}}
                                <td>{{ $call->approvedBy
                                    ? ($call->approvedBy->employee
                                        ? 'Employee:' . $call->approvedBy->employee->employee_id
                                        : ($call->approvedBy->customer
                                            ? 'Customer:' . ($call->approvedBy->customer->customer_code ?? $call->approvedBy->customer->customer_name)
                                            : $call->approvedBy->name))
                                    : '' }}
                                </td>
                                <td>
                                    @foreach ($call->refferTeamHeads as $rfTh)
                                    <small>{{$rfTh->name}}</small>,<br>
                                    @endforeach
                                    </td>
                            </tr>
                            {{-- modal Start  --}}

                            {{-- modal End  --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection



@push('js')
@endpush
