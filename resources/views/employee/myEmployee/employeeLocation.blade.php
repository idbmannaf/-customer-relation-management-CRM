@extends('employee.layouts.employeeMaster')
@push('title')
    | Employee Dashboard |  My Location
@endpush

@push('css')
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <div class="card-title">Locations of : {{$employee->name}} ({{$employee->employee_id}})</div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderd table-sm text-nowrap">
                    <thead>
                        <tr>
                            {{-- <th>ID</th> --}}
                            <th>Employee</th>
                            <th>Date Time</th>
                            <th>Lat</th>
                            <th>Lng</th>
                            <th>IP</th>
                            <th>Map</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($locations as $location)
                        <tr>
                            {{-- <td>{{$location->id}}</td> --}}
                            <td>{{$employee->name}} ({{$employee->employee_id}})</td>
                            <td>{{$location->created_at}}</td>
                            <td>{{$location->lat}}</td>
                            <td>{{$location->lng}}</td>
                            <td>{{$location->ip}}</td>
                            <td><a class="btn border" href="https://www.google.com/maps?q={{$location->lat}}+{{$location->lng}}"><i class="fas fa-map-marked-alt"></i> Map</a></td>
                            <td>{{$location->Location}}</td>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="7" class="text-danger"> No Location Found</th>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{$locations->render()}}
        </div>
    </div>
@endsection



@push('js')
@endpush
