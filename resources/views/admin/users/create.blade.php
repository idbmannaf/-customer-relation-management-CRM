@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Create Users
@endpush

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 m-auto">
            <div class="card">
                <div class="card-header bg-info">
                    <div class="card-title">Create User</div>
                </div>
                @include('alerts.alerts')
                <div class="card-body">
                    <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror ">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror ">
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror ">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="form-group">
                            <label for="role">Roles</label>
                            <select name="role" id="id" class="form-control @error('role') is-invalid @enderror ">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password"
                                class="form-control  @error('password') is-invalid @enderror ">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control  @error('password_confirmation') is-invalid @enderror ">
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="avater">Avater</label>
                            <input type="file" name="avater" id="avater">
                        </div>
                        <div class="form-group">
                            <label for="track_him"> <input type="checkbox" name="track_him" id="track_him"> Track
                                Him</label>
                        </div>
                        {{-- <div class="form-group">
                            <label for="attendance"> <input type="checkbox" name="attendance" id="attendance">Attendance</label>
                        </div> --}}
                        <div class="form-group">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('js')
@endpush
