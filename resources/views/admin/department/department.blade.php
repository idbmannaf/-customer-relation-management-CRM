@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Department
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Departments
                {{-- <a href="{{ route('admin.employee.create') }}" class="btn btn-danger"><i class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            <div class="card shadow">
                <div class="card-body">
                    {{-- <form action="{{route('admin.department.store',['type'=>'bulk'])}}" method="post" enctype="multipart/form-data" >
                        @csrf
                        <input type="file" name="file">
                        <input type="submit" class="btn btn-info" value="Bulk Upload">
                    </form> --}}
                    @can('department-add')
                    <form action="{{route('admin.department.store')}}" method="post" enctype="multipart/form-data" class="form-inline">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-2">
                                <input type="text" class="form-control" name="department" placeholder="Department Title">
                            </div>
                        </div>
                        <input type="submit" class="btn btn-info" value="Submit">
                    </form>
                    @endcan

                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>Action</th>
                        <th>Designation</th>

                    </thead>
                    <tbody>
                        @foreach ($departments as $department)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            @can('department-edit')
                                            <a class="dropdown-item" data-toggle="modal" data-target="#edit{{$department->id}}" data-whatever="@fat"
                                                href="{{ route('admin.department.edit', $department) }}" >Edit</a>
                                            @endcan

                                        @can('department-delete')
                                        <form action="{{ route('admin.department.destroy', $department) }}" method="POST">
                                            @method("DELETE")
                                            @csrf
                                            <input type="submit" style="background-color:transparent; border:none;"
                                                value="Delete"
                                                onclick="return confirm('Are you sure? you want to delete this Department?');">
                                        </form>
                                        @endcan


                                        </div>
                                    </div>
                                </td>

                                <td>{{$department->title}}</td>
                            </tr>

                             {{-- edit Modal Start --}}
                             <div class="modal fade" id="edit{{$department->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Edit {{$department->title}}</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin.department.update',$department)}}" method="post" enctype="multipart/form-data" class="form-inline">
                                            @csrf
                                            @method("PATCH")
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{$department->title}}" name="department" placeholder="department Title">
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-info" value="Submit">
                                            </div>

                                        </form>
                                    </div>

                                  </div>
                                </div>
                              </div>
                            {{-- edit Modal End --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
