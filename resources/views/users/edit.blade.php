@extends('users.layouts.userMaster')
@section('content')
<div class="card shadow">
    <div class="card-header bg-info">
        <div class="card-title">Edit User ({{ $user->name }})</div>
    </div>
    @include('alerts.alerts')
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 m-auto">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Edit User</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.updateUser', $user) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="user">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control  @error('name') is-invalid @enderror " placeholder="Name"
                                    id="name" name="name" type="text" value="{{ $user->name }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="form-group">
                                <label for="email">E-mail</label>
                                <input class="form-control  @error('email') is-invalid @enderror " placeholder="E-mail"
                                    id="email" name="email" type="email" value="{{ $user->email }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> --}}

                            <div class="form-group">
                                <label for="avater">Avater</label>
                                <input type="file" name="avater" id="avater">
                                <img src="{{ route('imagecache', ['template' => 'sbixs', 'filename' => $user->fi()]) }}"
                                    alt="">
                            </div>

                            <div class="form-group">
                                <input class="btn btn-info" type="submit" value="Update">
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-4 m-auto">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Edit/update Password</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.updateUser', $user) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="password">
                            <div class="form-group">
                                <label for="old_password">Old Password </label>
                                <input class="form-control @error('old_password') is-invalid @enderror "
                                    placeholder="Old Password" id="old_password" name="old_password" type="password" value="">
                                @error('old_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">New Password </label>
                                <input class="form-control @error('password') is-invalid @enderror "
                                    placeholder="Password" id="password" name="password" type="password" value="">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input class="form-control" placeholder="Confirmation Password"
                                    id="password_confirmation" name="password_confirmation" type="password" value="">
                            </div>

                            <div class="form-group">
                                <input class="btn btn-info" type="submit" value="Update Password">
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
