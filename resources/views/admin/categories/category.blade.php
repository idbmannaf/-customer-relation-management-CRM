@extends('admin.layouts.adminMaster')
@push('title')
    Product Categories
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">{{ucfirst(request()->type)}} Categories
            </div>
        </div>
        <div class="card-body">
            @include('alerts.alerts')
        <form action="{{route('admin.category.store',['type'=>request()->type])}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6 m-auto">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-1">
                            <input type="text" name="name" class="form-control" placeholder="Category Name">
                        </div>
                        <div class="col-12 col-md-6">
                            <input type="submit" class="btn btn-success">
                        </div>
                    </div>
                </div>
            </div>
        </form>
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>
                                Category name
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <div class="btn-group btn-sm">
                                        <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit{{$category->id}}" data-whatever="@fat">Edit</a>
                                        <form action="{{route('admin.category.destroy',$category)}}" method="POST">
                                        @csrf
                                    @method("DELETE")
                                    <button type="submit" onclick="return confirm('Are you sure? You want to delete this category?');"  class="btn btn-danger btn-xs">Delete</button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                            {{-- Edit modal Start --}}
                            <div class="modal fade" id="edit{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Edit Category: {{$category->name}}</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <form action="{{route('admin.category.update',$category)}}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group">
                                          <label for="name" class="col-form-label">Category Name</label>
                                          <input type="text" name="name" value="{{$category->name}}" class="form-control" id="name">
                                        </div>
                                        <div class="form-group">
                                         <input type="submit" class="btn btn-info" value="Update">
                                        </div>
                                      </form>
                                    </div>

                                  </div>
                                </div>
                              </div>
                            {{-- Edit modal End --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
              {{$categories->appends(['type'=>request()->type ?? ''])->render()}}
        </div>
    </div>
@endsection
