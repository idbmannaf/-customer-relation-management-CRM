@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard |  Location Create
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Locations Edit
            </div>
        </div>

        <div class="card-body">
            @include('alerts.alerts')
            <form action="{{ route('gUpdate', ['location' => $location]) }}" method="POST"
                enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="form-group">
                    <label for="location-input">Location Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        id="location-input" placeholder="Google Location" autocomplete="off"
                        value="{{ $location->name }}">
                </div>

                <div class="form-group">
                    <label for="lat">Latitude</label>
                    <input type="text" name="lat" class="form-control @error('lat') is-invalid @enderror " id="lat"
                        placeholder="Latitude Here" value="{{ $location->lat }}">
                </div>

                <div class="form-group">
                    <label for="lng">Longitude</label>
                    <input type="text" name="lng" class="form-control @error('lng') is-invalid @enderror " id="lng"
                        placeholder="Longitude Here" value="{{ $location->lng }}">
                </div>
                <div class="form-group ">
                    <input type="submit" value="Update" class="form-control btn btn-info">
                </div>
            </form>
        </div>
    </div>
@endsection
