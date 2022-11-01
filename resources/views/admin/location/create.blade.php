@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Location Create
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Locations Create
            </div>
        </div>
        <div class="card-body">
            @include('alerts.alerts')
            <form action="{{ route('gStore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                        <div class="form-group">
                                <label for="location-input">Location Name</label>
                                <input type="text" name="name"
                                    class="form-control pac-target-input @error('location') is-invalid @enderror"
                                    id="location-input" placeholder="Google Location" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="lat">Latitude</label>
                            <input type="text" name="lat"  id="lat1" class="form-control @error('lat') is-invalid @enderror "
                                placeholder="Latitude Here">
                        </div>
                        <div class="form-group">
                            <label for="lng">Longitude</label>
                            <input type="text" name="lng" id="lng1"  class="form-control @error('lng') is-invalid @enderror "
                                placeholder="Longitude Here">
                        </div>

                    <div class="form-group ">
                        <input type="submit" class="form-control btn btn-info">
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

