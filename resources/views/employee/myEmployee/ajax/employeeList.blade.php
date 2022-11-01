<div class="table-responsive">
    <table class="table table-bordered table-sm text-nowrap">
        <thead>
            <th>Action</th>
            <th>Active</th>
            <th>Employee ID</th>
            <th>Employee Password</th>
            <th>Employee Name</th>
            <th>Joining Date</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Company</th>
            <th>Team Head</th>
            <th>Track</th>
            <th>Attendance</th>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                Action
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @can('employee-edit')
                                <a class="dropdown-item"
                                href="{{ route('admin.employee.edit', $employee) }}">Edit</a>
                                @endcan

                                @can('employee-location')
                                <a class="dropdown-item"
                                href="{{ route('admin.user.employeeLocation', $employee) }}">Locations</a>
                                @endcan

                                @can('employee-attandance')
                                <a class="dropdown-item"
                                href="{{ route('admin.user.employeeAttaendance', $employee) }}">Attandance</a>
                                @endcan
                                @can('employee-office-visit')
                                <a class="dropdown-item"
                                href="{{ route('admin.user.employeeOfficeVisits', $employee) }}">Office Visits</a>
                                @endcan
                                {{-- @can('employee-customers') --}}
                                <a class="dropdown-item"
                                href="{{ route('admin.employeeCustomers', $employee) }}">Customers</a>
                                {{-- @endcan --}}

                                @can('employee-delete')
                                <form action="{{ route('admin.employee.destroy', $employee) }}" method="POST">
                                    @method("DELETE")
                                    @csrf
                                    <input type="submit" style="background-color:transparent; border:none;"
                                        value="Delete"
                                        onclick="return confirm('Are you sure? you want to delete this team?');">
                                </form>
                                @endcan


                            </div>
                        </div>
                    </td>

                    <td>
                        @if ($employee->active)
                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                        @endif
                    </td>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ $employee->user->temp_password }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->joining_date }}</td>
                    <td>{{ $employee->designation ? $employee->designation->title : '' }}</td>
                    <td>{{ $employee->department ? $employee->department->title : '' }}</td>
                    <td>{{ $employee->company ? $employee->company->name : '' }}</td>
                    <td>{{ $employee->teamHead ? $employee->teamHead->name : '' }}</td>
                    <td>
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
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $employees->appends(['q'=>$q])->render() }}
