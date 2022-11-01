@extends('admin.layouts.adminMaster')


@section('content')
    @include('alerts.alerts')
    <div class="card">
        <div class="card-header bg-info">
            <h4 class="card-title">Role Management</h4>
            <div class="card-tools">
                @can('role-create')
                <a class="btn btn-success btn-xs" href="{{ route('admin.roles.create') }}"> Create New Role</a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th width="280px">Action</th>
                    </tr>
                    @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a class="btn btn-info btn-xs" href="{{ route('admin.roles.show', $role->id) }}">Show</a>

                                <a class="btn btn-primary btn-xs"
                                    href="{{ route('admin.roles.edit', $role->id) }}">Edit</a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['admin.roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                {!! Form::close() !!}

                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            {!! $roles->render() !!}
        </div>

    </div>
@endsection
