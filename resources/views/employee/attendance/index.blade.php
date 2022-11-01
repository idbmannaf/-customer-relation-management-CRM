@extends('employee.layouts.employeeMaster')
@push('title')
    | Employee Dashboard | Attendance
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Attendance ({{date('Y-m-d')}})
                {{-- <a href="{{ route('admin.company.create') }}" class="btn btn-danger"><i
                        class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">

            @include('employee.attendance.ajax.attendanceList')
        </div>
    </div>
@endsection



@push('js')
@endpush
