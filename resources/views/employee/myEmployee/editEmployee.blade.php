@extends('employee.layouts.employeeMaster')
@push('title')
    | employee Dashboard | Edit Employee
@endpush

@push('css')
    <style>

    </style>
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Edit Employee:: {{$employee->name}} ({{$employee->employee_id}})
                <a href="{{ route('employee.myTeam') }}" class="btn btn-success"><i class="fas fa-eye"></i></a>
            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            <form action="{{ route('employee.myTeamEdit', $employee) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="name">Employee Name</label>
                    <input type="text" name="name" id="name" value="{{ $employee->name }}"
                        class="form-control @error('name') is-invalid @enderror('name') ">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" name="employee_id" id="employee_id"
                        value="{{ old('employee_id') ?? $employee->employee_id }}"
                        class="form-control @error('employee_id') is-invalid @enderror('employee_id') ">
                    @error('employee_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="employee_password">Employee Password</label>
                    <input type="password" name="employee_password" id="employee_password"
                        class="form-control @error('employee_password') is-invalid @enderror('employee_password') ">
                    @error('employee_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="text" name="mobile" id="mobile" value="{{ $employee->mobile }}"
                        class="form-control @error('mobile') is-invalid @enderror('mobile') ">
                    @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="{{ $employee->email }}"
                        class="form-control @error('email') is-invalid @enderror('email') ">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="joining_date">Joining Date</label>
                    <input type="date" name="joining_date" id="joining_date" value="{{ $employee->joining_date }}"
                        class="form-control @error('joining_date') is-invalid @enderror('joining_date') ">
                    @error('joining_date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="designation">Designation</label>
                    <select name="designation" id="designation"
                        class="form-control  @error('designation') is-invalid @enderror">
                        <option value="">Select Our designation</option>
                        @foreach ($designations as $designation)
                            <option {{ $employee->designation_id == $designation->id ? 'selected' : '' }}
                                value="{{ $designation->id }}">{{ $designation->title }}</option>
                        @endforeach
                        @error('designation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </select>
                </div>

                <div class="form-group">
                    <label for="department">Department</label>
                    <select name="department" id="department"
                        class="form-control  @error('department') is-invalid @enderror">
                        <option value="">Select Our Department</option>
                        @foreach ($departments as $department)
                            <option {{ $employee->department_id == $department->id ? 'selected' : '' }}
                                value="{{ $department->id }}">{{ $department->title }}</option>
                        @endforeach
                        @error('department')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </select>
                </div>




                <div class="form-group">
                    <label for="company">Company</label>
                    <select name="company" id="company" disabled data-url="{{ route('admin.loadLocationAjax') }}"
                        class="form-control company @error('company') is-invalid @enderror">
                        <option value="">Select Our Company</option>
                        @foreach ($companies as $company)
                            <option {{ $employee->company_id == $company->id ? 'selected' : '' }}
                                value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                        @error('company')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </select>
                    <span class="text-danger companyError"></span>
                </div>


                <div class="form-group OfficeLocation">
                    <label for="head_office">Attandance Office Locations</label>
                    @if ($employee->company->officeLocation->count() > 0)
                        <select name="head_office" id="head_office"
                            class="form-control @error('head_office') is-invalid @enderror">
                            <option value="">Select Office Location</option>
                            @foreach ($employee->company->officeLocation as $office_location)
                                <option
                                    {{ $office_location->id == $employee->user->office_location_id ? 'selected' : '' }}
                                    value="{{ $office_location->id }}">{{ $office_location->title }}
                                    ({{ $office_location->company->name }})
                            @endforeach
                        </select>
                    @else
                        <select name="head_office" id="head_office"
                            class="form-control @error('head_office') is-invalid @enderror">
                            <option value="">No Office Found</option>
                        </select>
                    @endif
                </div>

                {{-- <div class="form-group">
                    <label for="team_role">Role</label> <br>
                    <select name="team_role" id="team_role" class="form-control team_role"
                        data-url="{{ route('admin.teamAdminListAjax', $employee) }}"
                        data-employee-id="{{ $employee->id }}">
                        <option value="">Select Team Admin</option>
                        <option {{ $employee->team_admin ==1 ? 'selected' : '' }} value="1">Team Admin</option>
                        <option {{ $employee->team_admin ==0 ? 'selected' : '' }} value="0">Team Member</option>
                    </select>
                </div> --}}

                {{-- <div class="form-group">
                    <label for="team_role">Team Role</label> <br>
                    <select name="team_role" id="team_role" class="form-control team_role"
                        data-employee-id="{{ $employee->id }}">
                        <option value="">Select Team Admin</option>
                        <option {{ $employee->team_admin == 1 ? 'selected' : '' }} value="1">Team Admin</option>
                        <option {{ $employee->team_admin == 0 ? 'selected' : '' }} value="0">Team Member</option>
                    </select>
                </div> --}}

                {{-- <div class="form-group teamAdminShow">
                    <label for="team_admin_employee_id">Team Head</label> <br>
                    <select name="team_admin_employee_id" id="team_admin_employee_id"
                        class="form-control team_admin_employee_id">
                        <option value="">Select Team Head</option>
                        @foreach ($selected_team_admins as $admin)
                            <option {{ $admin->id == $employee->team_admin_id ? 'selected' : '' }}
                                value="{{ $admin->id }}">{{ $admin->name }}: {{ $admin->company->name }} :
                                {{ $admin->user->officeLocation ? $admin->user->officeLocation->title : '' }}</option>
                        @endforeach
                    </select>
                </div> --}}


                <div class="form-group">
                    <label for="avater">Avater</label>
                    <input type="file" name="avater" id="avater">
                    <br>
                    <img src="{{ route('imagecache', ['template' => 'sbixs', 'filename' => $employee->user->fi()]) }}"
                        alt="">
                </div>


                <div class="form-group">
                    <label for="track_him"> <input type="checkbox" {{ $employee->user->track ? 'checked' : '' }}
                            name="track_him" id="track_him"> Track
                        Him</label>
                </div>

                <div class="form-group">
                    <label for="attendance"> <input type="checkbox" {{ $employee->user->attendance ? 'checked' : '' }}
                            name="attendance" id="attendance">Attendance</label>
                </div>


                <div class="form-group">
                    <label for="active"> <input type="checkbox" {{ $employee->active ? 'checked' : '' }} name="active"
                            id="active"> Active</label>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-info">
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/custom.js') }}"></script>

@endpush


