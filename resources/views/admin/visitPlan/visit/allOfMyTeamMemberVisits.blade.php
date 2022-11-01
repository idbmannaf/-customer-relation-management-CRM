@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Visits
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Visits Of My Employees
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>Visit Plan Date</th>
                            <th>Visit Status</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Customer</th>
                            <th>Visit Date</th>
                            <th>Pupose Of Visit </th>
                            <th>Payment Collection Date </th>
                            <th>Payment Maturity Date </th>
                            <th>Achievment </th>
                            <th>Sale Details </th>
                            <th>Sale Amount </th>
                            <th>Collection Detils </th>
                            <th>Collection Amount</th>
                            <th>Previous Ledger balance </th>
                            <th>Current Ledger balance </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visits as $visit)
                            <tr>
                                <td>{{ $visit->date_time }} {{ $visit->addeBy_id }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if ($visit->addedBy_id == auth()->user()->id ||
                                                (auth()->user()->employee->isMyMember($visit->employee_id) &&
                                                    $visit->status == 'pending'))
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitEdit', ['visit_plan' => $visit->visit_plan, 'visit' => $visit]) }}">Edit</a>
                                            @endif

                                            @if (auth()->user()->employee->isMyMember($visit->employee_id) && $visit->status == 'pending')
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'approved']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Approv this Visit?')">Approved
                                                </a>

                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'rejected']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Reject this Visit?')">Rejected
                                                </a>
                                            @endif
                                            @if ($visit->visit_plan->visit_start_at && $visit->status == 'approved')
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.convayances', ['visit' => $visit]) }}">Convayance Bill Claim
                                                </a>
                                            @endif
                                        </div>

                                    </div>
                                </td>
                                <td>
                                    @if ($visit->visit_plan->visited_at)
                                    <div class="badge badge-success">Visited</div>
                                    @else
                                    <div class="badge badge-warning">Not Visited</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($visit->status == 'pending')
                                        <span class="text-warning">Pending</span>
                                    @elseif ($visit->status == 'approved')
                                        <span class="text-success">Approved</span>
                                    @else
                                        <span class="text-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $visit->customer ? $visit->customer->customer_name : '' }}</td>
                                <td>{{ $visit->visit_plan->date_time }}</td>
                                <td>{{ $visit->purpose_of_visit }}</td>
                                <td>{{ $visit->payment_collection_date }}</td>
                                <td>{{ $visit->payment_maturity_date }}</td>
                                <td>{{ $visit->achievement }}</td>
                                <td>{{ $visit->sale_details }}</td>
                                <td>{{ $visit->sale_amount }}</td>
                                <td>{{ $visit->collection_details }}</td>
                                <td>{{ $visit->collection_amount }}</td>
                                <td>{{ $visit->previous_ledger_balance }}</td>
                                <td>{{ $visit->current_ledger_balance }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
