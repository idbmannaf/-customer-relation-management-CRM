@extends('admin.layouts.adminMaster')


@section('content')

        {{-- MSG --}}
        <div class="card">
            <div class="card-header bg-info">
                <h4 class="card-title">Show Role</h4>
                <div class="card-tools">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.roles.index') }}"> Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $role->name }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Permissions:</strong>
                            @if(!empty($rolePermissions))
                                @foreach($rolePermissions as $v)
                                    <label class="label label-success">{{ showPermission($v->name) }},</label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


@endsection
