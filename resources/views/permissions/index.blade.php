@extends('admin.layouts.adminMaster')


@section('content')
    <div class="card card-primary">
        <div class="card-header bg-info">
            <h4 class="card-title">
                Permission Management
            </h4>

        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-5 m-auto">
                    <div class="card shadow">
                        <div class="card-body">
                            {!! Form::open(['route' => 'admin.permissions.store', 'method' => 'POST']) !!}
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Permission Name:</strong>
                                        {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 ">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif


            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th width="280px">Action</th>
                    </tr>
                    @foreach ($permissions as $key => $permission)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>
                                {{-- <a class="btn btn-info" href="{{ route('permissions.show',$permission->id) }}">Show</a> --}}

                                    <a class="btn btn-primary btn-xs"
                                        href="{{ route('admin.permissions.edit', $permission->id) }}">Edit</a>


                                    {!! Form::open(['method' => 'DELETE', 'route' => ['admin.permissions.destroy', $permission->id], 'style' => 'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                    {!! Form::close() !!}

                            </td>
                        </tr>
                    @endforeach
                </table>


            </div>


            {!! $permissions->render() !!}

        </div>

    </div>
@endsection
