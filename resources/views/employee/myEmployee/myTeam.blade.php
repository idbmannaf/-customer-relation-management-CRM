@extends('employee.layouts.employeeMaster')
@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <h4 class="text-center">My Team Members</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>Active</th>
                        <th>Action</th>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        @if (auth()->user()->employee->team_admin)
                            <th>Email</th>
                            <th>Phone</th>
                        @endif
                        <th>Joining Date</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Company</th>
                        {{-- <th>Track</th>
                        <th>Attendance</th> --}}
                    </thead>
                    <tbody>
                        @foreach ($team_members as $employee)
                            <tr style="min-height: 100px">

                                <td>
                                    @if ($employee->active)
                                        <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="{{ route('employee.myTeamEdit', $employee) }}">Edit</a>
                                            <a class="dropdown-item"
                                                href="{{ route('employee.myEmployeeCustomers', $employee) }}">Customers</a>
                                            <a class="dropdown-item"
                                                href="{{ route('employee.myEmployeeLocation', $employee) }}">Locations</a>

                                            <a class="dropdown-item"
                                                href="{{ route('employee.myEmployeeAttandace', $employee) }}">Attandance</a>

                                            <a class="dropdown-item"
                                                href="{{ route('employee.myEmployeeOfficeVisit', $employee) }}">Office
                                                Visit</a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->employee_id }}</td>
                                <td>{{ $employee->name }}</td>
                                @if (auth()->user()->employee->team_admin)
                                    <td>
                                        @if ($employee->email)
                                            <a title="{{ $employee->email }}" class="btn btn-success btn-xs"
                                                href="tel:{{ $employee->email }}"><i class="fas fa-envelope"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($employee->mobile)
                                            <a title="{{ $employee->mobile }}" class="btn btn-warning btn-xs"
                                                href="tel:{{ $employee->mobile }}"><i class="fas fa-phone-volume"></i></a>
                                        @endif
                                    </td>
                                @endif
                                <td>{{ $employee->joining_date }}</td>
                                <td>{{ $employee->designation ? $employee->designation->title : '' }}</td>
                                <td>{{ $employee->department ? $employee->department->title : '' }}</td>
                                <td>{{ $employee->company ? $employee->company->name : '' }}</td>
                                {{-- <td>
                                    @if ($employee->user->track)
                                        <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                                <td>
                                    @if ($employee->user->attendance)
                                        <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                    @endif
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $team_members->render() }}
        </div>
    </div>
    </div>
@endsection
