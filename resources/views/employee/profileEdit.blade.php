@extends('employee.layouts.employeeMaster')
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Edit Employee::
                <a href="{{ route('admin.employee.index') }}" class="btn btn-success"><i class="fas fa-eye"></i></a>
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">Edit</div>
                        <form action="{{ route('employee.updateMyProfile', ['employee' => $employee]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Employee Name</label>
                                <input type="text" name="name" id="name" value="{{ $employee->name }}"
                                    class="form-control @error('name') is-invalid @enderror('name') ">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="joining_date">Joining Date</label>
                                <input type="date" name="joining_date" id="joining_date"
                                    value="{{ $employee->joining_date }}"
                                    class="form-control @error('joining_date') is-invalid @enderror('joining_date') ">
                                @error('joining_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            {{-- <div class="form-group">
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
                        <select name="company" id="company" data-url="{{ route('admin.loadLocationAjax') }}"
                            class="form-control  @error('company') is-invalid @enderror">
                            <option value="">Select Our Company</option>
                            @foreach ($companies as $company)
                                <option {{ $employee->company_id == $company->id ? 'selected' : '' }}
                                    value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                            @error('company')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </select>
                    </div>
                    <div class="form-group OfficeLocation">
                        <label for="head_office">Office Locations</label>
                        @if ($employee->company->officeLocation->count() > 0)
                            <select name="head_office" id="head_office"
                                class="form-control @error('head_office') is-invalid @enderror">
                                <option value="">Select Office Location</option>
                                @foreach ($employee->company->officeLocation as $office_location)
                                    <option {{$office_location->id == $employee->user->office_location_id ? 'selected' : ''}} value="{{ $office_location->id }}">{{ $office_location->title }}
                                        ({{ $office_location->company->name }})
                                @endforeach
                            </select>
                            @else
                                <select name="head_office" id="head_office"
                                class="form-control @error('head_office') is-invalid @enderror">
                                <option value="">No Office Found</option>
                            </select>
                        @endif
                    </div> --}}
                            <div class="form-group">
                                <label for="avater">Avater</label>
                                <input type="file" name="avater" id="avater">
                                <br>
                                <img src="{{ route('imagecache', ['template' => 'sbixs', 'filename' => $employee->user->fi()]) }}"
                                    alt="">
                            </div>
                            {{-- <div class="form-group">
                        <label for="">Role</label> <br>
                        <label for="team_admin"><input type="radio" name="team_role" {{$employee->team_admin ? 'checked' : ''}} id="team_admin" value="team_admin"> Team Admin</label>
                        <label for="team_member"> <input type="radio" name="team_role" {{$employee->team_admin!=1 ? 'checked' : ''}} id="team_member" value="team_member"> Team Member</label>
                    </div>

                    <div class="form-group">
                        <label for="track_him"> <input type="checkbox" {{$employee->user->track ? 'checked' : ''}} name="track_him"  id="track_him"> Track
                            Him</label>
                    </div>

                    <div class="form-group">
                        <label for="attendance"> <input type="checkbox" {{$employee->user->attendance ? 'checked' : ''}} name="attendance" id="attendance">Attendance</label>
                    </div>


                    <div class="form-group">
                        <label for="active"> <input type="checkbox" {{ $employee->active ? 'checked' : '' }} name="active"
                                id="active"> Active</label>
                    </div> --}}

                            <div class="form-group">
                                <input type="submit" class="btn btn-info">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">Edit Password</div>
                        <div class="card-body">
                            <form action="{{ route('employee.updateMyProfile', ['employee' => $employee,'type'=>'password']) }}" method="POST" enctype="multipart/form-data">
                                @csrf()
                                <input type="hidden" name="type" value="password">
                                <div class="form-group">
                                    <label for="old_password">Old Password </label>
                                    <input class="form-control @error('old_password') is-invalid @enderror "
                                        placeholder="Old Password" id="old_password" name="old_password" type="password"
                                        value="">
                                    @error('old_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">Password </label>
                                    <input class="form-control @error('password') is-invalid @enderror "
                                        placeholder="Password" id="password" name="password" type="password" value="">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmation Password</label>
                                    <input class="form-control" placeholder="Confirmation Password"
                                        id="password_confirmation" name="password_confirmation" type="password" value="">
                                </div>

                                <div class="form-group">
                                    <input class="btn btn-info" type="submit" value="Update Password">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).on('change', '#company', function(e) {
            var company = $(this).val();
            var url = $(this).attr('data-url');
            var finalUrl = url + "?company=" + company;
            $.ajax({
                url: finalUrl,
                methods: 'GET',
                success: function(res) {

                    if (res.success) {
                        $(".OfficeLocation").html(res.html);
                    } else {
                        var html = `<label for="head_office">Office Locations</label>
                <select name="head_office" id="head_office" class="form-control">
                    <option value="">No Office Location found</option>
                </select>`;
                        $(".OfficeLocation").html(html);
                    }
                }
            })
        })
    </script>
@endpush
