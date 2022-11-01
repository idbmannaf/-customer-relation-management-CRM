@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard |  Location
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> Locations
                {{-- @can('office-location-add') --}}
                <a href="{{ route('gCreate') }}" class="btn btn-danger"><i class="fas fa-plus"></i></a>
                {{-- @endcan --}}
            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>Action</th>
                        <th>Location Name</th>
                        <th>Latitude</th>
                        <th>Longitude</th>

                        {{-- <th>Image</th> --}}

                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="{{ route('gEdit', $location) }}">Edit</a>

                                            <form action="{{ route('gDestroy', $location) }}" method="POST">
                                                @method("DELETE")
                                                @csrf
                                                <input type="submit" style="background-color:transparent; border:none;"
                                                    value="Delete"
                                                    onclick="return confirm('Are you sure? you want to delete this team?');">
                                            </form>


                                        </div>
                                    </div>
                                </td>
                                <td> {{$location->name}}</td>
                                <td> {{$location->lat}}</td>
                                <td> {{$location->lng}}</td>
                                {{-- <td>
                                <img src="{{ route('imagecache', [ 'template'=>'medium','filename' => $location->fi() ]) }}" alt="">
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
