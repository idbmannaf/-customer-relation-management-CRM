@extends('admin.layouts.adminMaster')
@push('title')
    Admin Dashboard | Edit User Roles
@endpush

@push('css')
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <div class="card-title">User : {{ $user->username }}</div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('admin.user.assignrole', $user) }}" method="POST">
                @csrf
                <div class="form-group">
                    <select name="role_name" id="role" class="form-control">
                        <option value="no_role" {{ count($user->roles) == 0 ? 'selected' : '' }}>No Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-info">
                </div>
            </form>
        </div>
    </div>
@endsection



@push('js')
@endpush
