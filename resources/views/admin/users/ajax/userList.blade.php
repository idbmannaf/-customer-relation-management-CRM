<div class="table-responsive">
    <table class="table table-bordered table-sm text-nowrap">
        <thead>
            <tr>
                <th>ID</th>
                <th>Action</th>
                <th>Name</th>
                <th>Username</th>
                <th>Temp Password</th>
                <th>Roles</th>
                {{-- <th>Permissions</th> --}}
                <th>User Type</th>
                <th>Track</th>
                {{-- <th>Attendance</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button"
                                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                @can('user-edit')
                                <a class="dropdown-item" href="{{ route('admin.user.edit', $user) }}">Edit</a>
                                @endcan
                                {{-- <a class="dropdown-item" href="{{ route('admin.user.attaendance', $user) }}">Attendance</a> --}}
                                @can('user-location')
                                    @if ($user->track)
                                        <a class="dropdown-item"
                                            href="{{ route('admin.user.location', $user) }}">Location</a>
                                    @endif
                                @endcan

                                @if (!$user->office_location_id)
                                {{-- @can('user-to-asign-employee') --}}
                                    {{-- <a class="dropdown-item" href="{{ route('admin.user.location', $user) }}">Assign
                                        To Employee</a> --}}
                                {{-- @endcan --}}


                                @endif

                                @can('user-delete')
                                    <form action="{{ route('admin.user.destroy', ['user' => $user]) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <input type="submit" style="background-color:transparent; border:none;"
                                            value="Delete"
                                            onclick="return confirm('Are you sure? you want to delete this team?');">
                                    </form>
                                @endcan
                                @can('user-assign-role')
                                <a class="dropdown-item" href="{{ route('admin.user.assignrole', $user) }}">Assign
                                    Role</a>
                                    @endcan

                            </div>
                        </div>
                    </td>

                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->temp_password }}</td>
                    <td>
                        @if (!empty($user->roles))
                            @foreach ($user->roles as $role)
                                <a href="{{ route('admin.roles.show', $role->id) }}" class="badge badge-primary"
                                    data-toggle="modal"
                                    data-target="#viwRole{{ $role->id }}">{{ $role->name }}</a>
                            @endforeach
                        @endif
                    </td>
                    {{-- <td>
                        @foreach ($user->getAllPermissions() as $permission)
                            <span class="badge badge-info">{{ showPermission($permission->name) }}</span>
                        @endforeach
                    </td> --}}

                    <td>
                        @if ($user->customer)
                            <span class="badge badge-success">Client</span>
                        @elseif ($user->employee)
                            <span class="badge badge-succ">Employee</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->track)
                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                        @endif
                    </td>
                    {{-- <td>
                        @if ($user->attendance)
                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                        @endif
                    </td> --}}
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
                                            <label
                                                class="label label-success">{{ showPermission($permission->name) }},
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
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <strong>Select Roles:</strong> <br>
                                    @foreach ($roles as $role_item)
                                        <label for="{{ $role_item->name }}"> <input type="checkbox" name="roles[]"
                                                value="{{ $role_item->name }}" id="{{ $role_item->name }}">
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
{{ $users->appends(['q' => $q])->render() }}
