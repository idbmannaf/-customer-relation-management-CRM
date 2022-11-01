@extends('admin.layouts.adminMaster')
@push('title')
    Admin Dashboard | Visit Plan
@endpush



@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> Visit Plans
                @can('visit-plan-add')
                    <a href="{{ route('admin.visitPlan.create') }}" class="btn btn-danger">New Visit Plan</a>
                @endcan
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
                            <td>Status</td>
                            <td>Call</td>
                            <th>Employee</th>
                            <th>Date Time</th>
                            <th>Purpose Of Visit</th>
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
                                                href="{{ route('admin.visits', $visit_plan) }}">Visits</a>
                                            @if ($visit_plan->status == 'pending')
                                                @if ('visit-update')
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.visitPlan.edit', $visit_plan) }}">Edit</a>
                                                @endif
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
                                <td>
                                    {{ $visit_plan->employee->name }}
                                </td>
                                <td>{{ $visit_plan->date_time }}</td>
                                <td>{{ $visit_plan->purpose_of_visit }}</td>
                                <td>{{ $visit_plan->payment_collection_date }}</td>
                                <td>{{ $visit_plan->payment_maturity_date }}</td>
                                <td>
                                    @if ($visit_plan->team_admin_approved_at)
                                        {{ $visit_plan->team_admin_approved_at }}
                                    @endif


                                </td>
                            </tr>
                            @if ($visit_plan->call)
                                @include('global.callModal')
                            @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
