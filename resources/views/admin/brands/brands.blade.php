@extends('admin.layouts.adminMaster')
@push('title')
    Product Brands
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">{{ucfirst(request()->type)}} Brands
            </div>
        </div>
        <div class="card-body">
            @include('alerts.alerts')
        <form action="{{route('admin.brand.store',['type'=>request()->type])}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6 m-auto">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-1">
                            <input type="text" name="name" class="form-control" placeholder="brand Name">
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
                                brand name
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>{{ $brand->name }}</td>
                                <td>
                                    <div class="btn-group btn-sm">
                                        <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#edit{{$brand->id}}" data-whatever="@fat">Edit</a>
                                        <form action="{{route('admin.brand.destroy',$brand)}}" method="POST">
                                        @csrf
                                    @method("DELETE")
                                    <button type="submit" onclick="return confirm('Are you sure? You want to delete this brand?');" class="btn btn-danger btn-xs">Delete</button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                            {{-- Edit modal Start --}}
                            <div class="modal fade" id="edit{{$brand->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Edit brand: {{$brand->name}}</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <form action="{{route('admin.brand.update',$brand)}}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group">
                                          <label for="name" class="col-form-label">brand Name</label>
                                          <input type="text" name="name" value="{{$brand->name}}" class="form-control" id="name">
                                        </div>
                                        <div class="form-group">
                                         <input type="submit" class="btn btn-info"  value="Update">
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
            {{$brands->appends(['type'=>request()->type ?? ''])->render()}}
        </div>
    </div>
@endsection
