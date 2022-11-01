@extends('admin.layouts.adminMaster')


@section('content')
    @include('alerts.alerts')
    <div class="card">
        <div class="card-header bg-info">
            <h4 class="card-title">Edit Role</h4>
            <div class="card-tools">
                <a class="btn btn-primary btn-xs" href="{{ route('admin.roles.index') }}"> Back</a>
            </div>
        </div>
        <div class="card-body">
            {!! Form::model($role, ['method' => 'PATCH', 'route' => ['admin.roles.update', $role->id]]) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control','readonly'=>true]) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Permission:</strong>
                        <br />
                        @foreach ($permission as $value)
                            <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'name']) }}
                                {{ showPermission($value->name) }}</label>
                            <br />
                        @endforeach
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
