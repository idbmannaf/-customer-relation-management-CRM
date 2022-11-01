@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Designations
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Designations
                {{-- <a href="{{ route('admin.employee.create') }}" class="btn btn-danger"><i class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            <div class="card shadow">
                <div class="card-body">
                    {{-- <a href="" class="btn btn-info" data-toggle="modal" data-target="#bulkUpload">Bulk Upload</a>  Or --}}
                    @can('designation-add')
                    <form action="{{ route('admin.designation.store') }}" method="post" enctype="multipart/form-data"
                        class="form-inline">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-2">
                                <input type="text" class="form-control" name="designation" placeholder="Designation Title">
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
                        @foreach ($designations as $designation)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            @can('designation-edit')
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.designation.edit', $designation) }}"
                                                    class="btn btn-primary" data-toggle="modal"
                                                    data-target="#edit{{ $designation->id }}" data-whatever="@fat">Edit</a>
                                            @endcan

                                            @can('designation-delete')
                                                <form action="{{ route('admin.designation.destroy', $designation) }}"
                                                    method="POST">
                                                    @method("DELETE")
                                                    @csrf
                                                    <input type="submit" style="background-color:transparent; border:none;"
                                                        value="Delete"
                                                        onclick="return confirm('Are you sure? you want to delete this Designation?');">
                                                </form>
                                            @endcan


                                        </div>
                                    </div>
                                </td>

                                <td>{{ $designation->title }}</td>
                            </tr>

                            {{-- edit Modal Start --}}
                            <div class="modal fade" id="edit{{ $designation->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit {{ $designation->title }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('admin.designation.update', $designation) }}"
                                                method="post" enctype="multipart/form-data" class="form-inline">
                                                @csrf
                                                @method("PATCH")
                                                <div class="form-group">
                                                    <input type="text" class="form-control"
                                                        value="{{ $designation->title }}" name="designation"
                                                        placeholder="Designation Title">
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
