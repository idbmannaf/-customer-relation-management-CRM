@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Create Users
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Edit User ({{ $user->name }})</div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 m-auto">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title">Edit User</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.user.update', $user) }}"
                                enctype="multipart/form-data">
                                @method("PATCH")
                                @csrf
                                <input type="hidden" name="type" value="user">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input class="form-control  @error('name') is-invalid @enderror " placeholder="Name"
                                        id="name" name="name" type="text" value="{{ $user->name }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- <div class="form-group">
                                    <label for="username">Username</label>
                                    <input class="form-control  @error('username') is-invalid @enderror " placeholder="username"
                                        id="username" name="username" type="text" value="{{ $user->username }}">
                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> --}}
                                {{-- <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input class="form-control  @error('email') is-invalid @enderror " placeholder="E-mail"
                                        id="email" name="email" type="email" value="{{ $user->email }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> --}}
                                {{-- <div class="form-group">
                                    <label for="head_office">Office Locations</label>
                                    <select name="head_office" id="head_office" class="form-control @error('head_office') is-invalid @enderror">
                                        <option value="">Select Head Office</option>
                                        @foreach ($office_locations as $office_location)
                                            <option {{$office_location->id == $user->office_location_id ? 'selected' :
                                            ''}} value="{{$office_location->id}}">{{$office_location->title}} ({{$office_location->company->name}})</option>
                                        @endforeach
                                    </select>

                                    @error('head_office')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> --}}
                                <div class="form-group">
                                    <label for="avater">Avater</label>
                                    <input type="file" name="avater" id="avater">
                                    <img src="{{ route('imagecache', ['template' => 'sbixs', 'filename' => $user->fi()]) }}"
                                        alt="">
                                </div>
                                <div class="form-group">
                                    <label for="track_him"> <input type="checkbox" name="track_him"
                                            {{ $user->track ? 'checked' : '' }} id="track_him"> Track
                                        Him</label>
                                </div>
                                {{-- <div class="form-group">
                                    <label for="attendance"> <input type="checkbox" name="attendance"
                                            {{ $user->attendance ? 'checked' : '' }} id="attendance">Attendance</label>
                                </div> --}}
                                <div class="form-group">
                                    <input class="btn btn-info" type="submit" value="Update">
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-md-4 m-auto">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title">Edit/update Password</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.user.update', $user) }}">
                                @method("PATCH")
                                @csrf
                                <input type="hidden" name="type" value="password">
                                <div class="form-group">
                                    <label for="password">Password <small>({{ $user->temp_password }})</small></label>
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
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-info">Team Role Add</div>
                <div class="card-body">
                    <form action="{{ route('admin.userUpdate', ['user' => $user, 'type' => 'team_role']) }}"
                        method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="company">Select Company</label>
                            <select name="company" id="company" class="form-control"
                                data-url="{{ route('admin.user.CompanyTeams', ['user' => $user->id]) }}">
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="showTeam">

                        </div>
                        <div class="form-group shoAdminOrMember">

                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-info" value="Update">
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-info">Team Roles of User {{ $user->name }}</div>
                <div class="card-body">
                    <table class="table table-borderd">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Team Name</th>
                                <th>Role Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->team_roles as $role)
                                <tr>
                                    <td>{{ $role->team ? $role->team->company->name : '' }}</td>
                                    <td>{{ $role->team ? $role->team->name : '' }}</td>
                                    <td>{{ $role->role_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
@endsection



@push('js')
    <script>
        $(document).on('change', '#company', function() {
            var that = $(this);
            $(".shoAdminOrMember").hide();
            var company = that.val();
            if (company == '') {
                $('.showTeam').hide();
            } else {
                $('.showTeam').show();
            }
            var url = that.attr('data-url');
            var finalUrl = url + "?type=company&company=" + company
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    $('.showTeam').html(res);
                }
            })

        })
        $(document).on('change', '#team', function() {
            var that = $(this);
            var team = that.val();
            var url = that.attr('data-url');
            var finalUrl = url + "?type=team&team=" + team + "&company=" + $("#company").val()
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    $(".shoAdminOrMember").show();
                    $('.shoAdminOrMember').html(res);
                }
            })

        })
    </script>
@endpush
