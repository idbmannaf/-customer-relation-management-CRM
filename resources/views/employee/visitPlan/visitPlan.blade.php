@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard | Visit Plans
@endpush



@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> Visit Plans
                <a href="{{ route('employee.customerVisit.create') }}" class="btn btn-danger">New Visit Plan</a>
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>Serial No</th>
                            <th>Action</th>
                            <td>Call</td>
                            <td>Status</td>
                            @if ($employee->team_admin)
                                <th>Employee</th>
                            @endif
                            <th>Date Time</th>
                            <th>Purpose Of Visit</th>
                            <th>Service Address</th>
                            <th>Customer</th>
                            <th>Payment Collection Date</th>
                            <th>Payment Maturity Date</th>
                            <th>Approved At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visit_plans as $visit_plan)
                            <tr>

                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="{{ route('employee.customerVisits', $visit_plan) }}">Visits</a>

                                            @if (!$visit_plan->team_admin_approved_at && in_array($visit_plan->addedBy_id, $addeByArray))
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisit.edit', ['type' => $type, 'visit' => $visit_plan]) }}">Edit</a>
                                            @endif
                                        </div>

                                    </div>
                                </td>
                                <td>
                                    @if ($visit_plan->status == 'pending')
                                        <span class="badge badge-danger">Pending</span>
                                    @elseif ($visit_plan->status == 'approved')
                                        <span class="badge badge-warning">Approved</span>
                                    @elseif ($visit_plan->status == 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($visit_plan->call)
                                        <a href="" class="btn btn-info btn-xs" data-toggle="modal"
                                            data-target="#vp-{{ $visit_plan->id }}" data-whatever="@fat">Service Details</a>
                                    @endif
                                </td>
                                @if ($employee->team_admin)
                                    <td>
                                        @if ($visit_plan->employee_id == $employee->id)
                                            <span class="text-success">{{ $visit_plan->employee->name }}</span>
                                        @else
                                            {{ $visit_plan->employee->name }}
                                        @endif

                                    </td>
                                @endif

                                <td>{{ $visit_plan->date_time }}</td>
                                <td>{{ $visit_plan->purpose_of_visit }}</td>
                                <td>{{ $visit_plan->service_address }}</td>
                                <td>{{ $visit_plan->customer ? $visit_plan->customer->customer_name : '' }}</td>
                                <td>{{ $visit_plan->payment_collection_date }}</td>
                                <td>{{ $visit_plan->payment_maturity_date }}</td>
                                <td>
                                    @if ($visit_plan->team_admin_approved_at)
                                       {{ $visit_plan->team_admin_approved_at }}
                                    @endif


                                </td>
                                @if ($visit_plan->call)
                                    @include('global.callModal')
                                @endif
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            {{ $visit_plans->render() }}
        </div>
    </div>
@endsection
