@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Office Location
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">RFID Devices Of Office: ({{ $location->title }}) and Company: ({{ $location->company->name }})

            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            <form action="{{ route('admin.rfidDeviceAdd', $location) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-7 m-auto pb-3">
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <input type="text" name="device_name" placeholder="RFID Device Name.."
                                    class="form-control @error('device_name') is-invalid @enderror">
                                @error('device_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="active"><input type="checkbox" name="active" id="active"> Active</label>
                                <input type="submit" class="btn btn-success">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>Action</th>
                        <th>Device Name</th>
                        <th>Company Location</th>
                        <th>Active</th>
                        {{-- <th>Image</th> --}}

                    </thead>
                    <tbody>
                        @foreach ($rfid_devices as $rfid_device)
                            <tr>
                                <td>
                                    <a href="" data-toggle="modal" data-target="#edit{{ $rfid_device->id }}"
                                        data-whatever="@fat" class="text-info"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.rfidDeviceDelete', ['rfid' => $rfid_device, 'location' => $location]) }}"
                                        class="text-danger" onclick="return confirm('are you sure? You want to Delete this RFID Device?');"><i class="fas fa-trash"></i></a>
                                </td>
                                <td>{{ $rfid_device->device_name }}</td>
                                <td>{{ $rfid_device->office_location->title }}</td>
                                <td>{{ $rfid_device->active ? 'actived' : 'inActived' }}</td>
                            </tr>
                            {{-- Modal Start --}}
                            <div class="modal fade" id="edit{{ $rfid_device->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit
                                                Device:{{ $rfid_device->device_name }} </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form
                                                action="{{ route('admin.rfidDeviceUpdate', ['rfid' => $rfid_device, 'location' => $location]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="form-group">
                                                    <label for="device_name" class="col-form-label">Device Name:</label>
                                                    <input type="text" name="device_name" placeholder="RFID Device Name.." value="{{$rfid_device->device_name}}"
                                                        class="form-control @error('device_name') is-invalid @enderror">
                                                    @error('device_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="active2"><input type="checkbox" name="active" id="active2" {{$rfid_device->active ? 'checked' : ''}}> Active</label>
                                                </div>
                                                <div class="form-group">
                                                   <input type="submit" value="Update" class="btn btn-success">
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- Modal End --}}
                        @endforeach
                    </tbody>
                </table>
                <div class="float-right pt-3">
                    {{$rfid_devices->render()}}
                </div>
            </div>
        </div>
    </div>
@endsection
