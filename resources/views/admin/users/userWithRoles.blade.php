@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | User With Roles
@endpush

@push('css')
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <div class="card-title">All Users</div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            {{-- <div class="row">
                <div class="col-12 col-md-3 mb-2">
                    <input type="text" name="q" id="search" class="form-control" placeholder="Search Username or Name"
                        data-url="{{ route('admin.user.search') }}">
                </div>
            </div> --}}
            <div class="showUser">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-wrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                {{-- <th>Temp Password</th> --}}
                                <th>Roles</th>
                                <th>Permissions</th>
                                <th>Track</th>
                                {{-- <th>Attendance</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>

                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    {{-- <td>{{ $user->temp_password }}</td> --}}
                                    <td>
                                        @if (!empty($user->roles))
                                            @foreach ($user->roles as $role)
                                                <a href="{{ route('admin.roles.show', $role->id) }}"
                                                    class="badge badge-primary" data-toggle="modal"
                                                    data-target="#viwRole{{ $role->id }}">{{ $role->name }}</a>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @foreach ($user->getAllPermissions() as $permission)
                                            <span class="badge badge-info">{{ showPermission($permission->name) }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($user->track)
                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                        @else
                                            <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                        @endif
                                    </td>
                                </tr>
                                {{-- //See Modal Start --}}
                                @if (count($user->roles))
                                    <div class="modal fade" id="viwRole{{ $role->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Role: Admin</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <strong>Permissions:</strong>
                                                        @foreach ($role->permissions as $permission)
                                                            <label class="label label-success">{{ showPermission($permission->name) }},
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="modal fade" id="assignRole{{ $user->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Role: Admin</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <strong>Select Roles:</strong> <br>
                                                    @foreach ($roles as $role_item)
                                                        <label for="{{ $role_item->name }}"> <input type="checkbox"
                                                                name="roles[]" value="{{ $role_item->name }}"
                                                                id="{{ $role_item->name }}">
                                                            {{ $role_item->name }}</label>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- //See Modal End --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $users->render() }}

            </div>
        </div>
    </div>
@endsection



@push('js')
    <script>
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    $('.showUser').html(res);
                }
            })
        })

        $(document).on('input', '#search', function(e) {
            var q = $(this).val();
            var url = $(this).attr('data-url');
            var finalUrl = url + "?q=" + q;
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    $('.showUser').html(res);
                }
            })
        })
    </script>
@endpush
