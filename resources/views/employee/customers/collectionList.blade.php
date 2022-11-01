@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Visits
@endpush

@section('content')
    <div class="card">

        @include('alerts.alerts')
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>Visit Plan Date</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Collection Amount</th>
                            <th>Customer</th>
                            <th>Visit Date</th>
                            <th>Pupose Of Visit </th>
                            <th>Remarks</th>
                            <th>Current Ledger balance </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collections as $visit)
                            <tr @if (count($collections) < 4) style="height: 250px;" @endif>
                                <td>{{ $visit->visit_plan->date_time }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="{{ route('employee.customerVisitview', ['visit_plan' => $visit->visit_plan, 'visit' => $visit]) }}">Details</a>
                                            @if ($visit->status == 'pending')
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'approved']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Approved this Visit?')">Approved & Completed
                                            @endif
                                            </a>
                                        </div>

                                    </div>
                                </td>
                                <td>
                                    @if ($visit->status == 'pending')
                                        <span class="text-warning">Pending</span>
                                    @elseif ($visit->status == 'confirmed')
                                        <span class="text-success">Approved</span>
                                    @elseif ($visit->status == 'approved')
                                        <span class="text-success">Approved</span>
                                    @elseif ($visit->status == 'rejected')
                                        <span class="text-danger">Rejected</span>
                                    @elseif ($visit->status == 'completed')
                                        <span class="text-success">Completed</span>
                                    @endif
                                </td>
                                <td>{{ $visit->collection_amount }}</td>
                                <td>{{ $visit->customer ? $visit->customer->customer_name : '' }}</td>

                                <td>{{ $visit->date_time }}</td>
                                <td>{{ $visit->purpose_of_visit }}</td>

                                <td>{{ $visit->remarks }}</td>
                                <td>{{ $visit->current_ledger_balance }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
