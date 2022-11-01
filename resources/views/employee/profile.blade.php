@extends('employee.layouts.employeeMaster')
@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <h4 class="text-center">My Profile</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-sm nowarap">
                <tr>
                    <th>Name</th>
                    <th>{{ $employee->name }}</th>
                </tr>
                <tr>
                    <th>Employee Id</th>
                    <th>{{ $employee->employee_id }}</th>
                </tr>
                <tr>
                    <th>Company</th>
                    <th>{{ $employee->company ? $employee->company->name : '' }}</th>
                </tr>
                <tr>
                    <th>Department</th>
                    <th>{{ $employee->department ? $employee->department->title : '' }}</th>
                </tr>
                <tr>
                    <th>Designation</th>
                    <th>{{ $employee->designation ? $employee->designation->title : '' }}</th>
                </tr>
                <tr>
                    <th>Team Role</th>
                    <th>
                        @if ($employee->team_admin)
                            <span class="badge badge-success"> Team Admin</span>
                        @else
                            <span class="badge badge-danger"> Team Member</span>
                        @endif
                    </th>
                </tr>
                <tr>
                    <th>Joining Date</th>
                    <th>{{ $employee->joining_date }}</th>
                </tr>
                <tr>
                    <th>Active</th>
                    <th>
                        @if ($employee->active)
                            <span class="badge badge-success"> Actived</span>
                        @else
                            <span class="badge badge-danger"> InActived</span>
                        @endif
                    </th>
                </tr>
            </table>
        </div>
    </div>
    </div>
@endsection
