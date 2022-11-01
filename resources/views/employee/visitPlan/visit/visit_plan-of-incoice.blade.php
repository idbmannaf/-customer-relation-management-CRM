@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard | Visit Plans
@endpush



@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> Visit Plans of Invoice ({{$invoice->id}})
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
                            <th>Employee</th>
                            <th>Date Time</th>
                            <th>Purpose Of Visit</th>
                            <th>Payment Collection Date</th>
                            <th>Payment Maturity Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visit_plans as $visit_plan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a class="btn btn-success btn-sm"
                                    href="{{ route('employee.customerVisits', $visit_plan) }}">Visits</a>
                                </td>

                                    <td>
                                        {{ $visit_plan->employee ? $visit_plan->employee->name : '' }}

                                    </td>

                                <td>{{ $visit_plan->date_time }}</td>
                                <td>{{ $visit_plan->purpose_of_visit }}</td>
                                <td>{{ $visit_plan->payment_collection_date }}</td>
                                <td>{{ $visit_plan->payment_maturity_date }}</td>
                                <td>
                                    @if ($visit_plan->team_admin_approved_at)
                                        Approved: {{ $visit_plan->team_admin_approved_at }}
                                    @elseif ($employee->team_admin && !$visit_plan->team_admin_approved_at)
                                        <a href="{{ route('employee.customerVisitPlanStatusUpdate', ['status' => 'approved', 'type' => $type, 'visit' => $visit_plan]) }}"
                                            onclick="return confirm('are you Sure You Want To Approve?')"
                                            class="btn btn-sm btn-success">Approved</a>
                                    @else
                                        Pending
                                    @endif


                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
