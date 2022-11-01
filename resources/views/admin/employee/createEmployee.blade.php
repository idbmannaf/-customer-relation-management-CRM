@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Create Employees
@endpush

{{-- @push('css')
    <link href="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css') }}"
        rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ asset('https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css') }}">
@endpush --}}
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Create Employee
                <a href="{{ route('admin.employee.index') }}" class="btn btn-success"><i class="fas fa-eye"></i></a>
            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            <form action="{{ route('admin.employee.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Employee Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror('name') ">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" name="employee_id" id="employee_id"
                        class="form-control @error('employee_id') is-invalid @enderror('employee_id') ">
                    @error('employee_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="rfid">RFID</label>
                    <input type="number" name="rfid" id="rfid"
                        class="form-control @error('rfid') is-invalid @enderror('rfid') ">
                    @error('rfid')
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
                    <input type="text" name="mobile" id="mobile"
                        class="form-control @error('mobile') is-invalid @enderror('mobile') ">
                    @error('mobile')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror('email') ">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="joining_date">Joining Date</label>
                    <input type="date" name="joining_date" id="joining_date"
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
                            <option value="{{ $designation->id }}">{{ $designation->title }}</option>
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
                            <option value="{{ $department->id }}">{{ $department->title }}</option>
                        @endforeach
                        @error('department')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </select>
                </div>

                <div class="form-group">
                    <label for="company">Company</label>
                    <select name="company" data-url="{{ route('admin.loadLocationAjax') }}" id="company"
                        class="form-control company @error('company') is-invalid @enderror">
                        <option value="">Select Our Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                        @error('company')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </select>
                </div>

                <div class="form-group OfficeLocation">

                </div>

                <div class="form-group">
                    <label for="team_role">Team Role</label> <br>
                    <select name="team_role" id="team_role" class="form-control team_role">

                    </select>
                </div>
                <div class="form-group teamAdminShow">
                    {{-- @if ($employee->team_admin == 0) --}}
                    <label for="team_admin_employee_id">Team Head</label> <br>
                    <select name="team_admin_employee_id" id="team_admin_employee_id"
                        class="form-control team_admin_employee_id">

                    </select>
                    {{-- @endif --}}
                </div>
                <div class="form-group">
                    <label for="avater">Avater</label>
                    <input type="file" name="avater" id="avater">
                </div>
                {{-- <div class="form-group">
                    <label for="">Role</label> <br>
                    <label for="team_admin"><input type="radio" name="team_role" id="team_admin" value="team_admin"> Team Admin</label>
                    <label for="team_member"> <input type="radio" name="team_role" id="team_member" value="team_member"> Team Member</label>
                </div> --}}

                <div class="form-group">
                    <label for="track_him"> <input type="checkbox" name="track_him" id="track_him"> Track
                        Him</label>
                </div>
                <div class="form-group">
                    <label for="attendance"> <input type="checkbox" name="attendance" id="attendance">Attendance</label>
                </div>
                <div class="form-group">
                    <label for="active"> <input type="checkbox" name="active" id="active"> Active</label>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-info">
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script>
        if ($('#team_role').val() == 1) {
            $(".teamAdminShow").hide();
        } else {
            $(".teamAdminShow").show();
        }
    </script>
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
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var team_roles = <?php echo json_encode($team_roles); ?>;
            var team_admins = <?php echo json_encode($team_admins); ?>;

            $(document).on("change", ".company", function(e) {
                // e.preventDefault();

                var that = $(this);
                var q = that.val();
                that.closest('form').find(".team_role").empty().append($('<option>', {
                    value: '',
                    text: 'Team Role'
                }));

                that.closest('form').find(".team_admin_employee_id").empty().append($('<option>', {
                    value: '',
                    text: 'Team Head'
                }));

                $.each(team_roles, function(i, item) {
                    that.closest('form').find(".team_role").append(
                        "<option value='" + i + "'>" + item +
                        "</option>");
                });

                $.each(team_admins, function(i, item) {

                    console.log(item.company_id);
                    if (item.company_id == q) {
                        that.closest('form').find(".team_admin_employee_id").append(
                            "<option value='" + item.id + "'>" + item.name +
                            "</option>");
                    }
                });

            });


            $(document).on("change", ".team_role", function(e) {
                // e.preventDefault();

                var that = $(this);
                var q = that.val();

                if (q == 1) {
                    $(".teamAdminShow").hide();
                } else {
                    $(".teamAdminShow").show();
                }

            });


        });
    </script>
@endpush
