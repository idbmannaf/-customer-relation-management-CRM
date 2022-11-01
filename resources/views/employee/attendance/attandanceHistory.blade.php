@extends('employee.layouts.employeeMaster')
@push('title')
    | employee Dashboard | Attendance
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Attendance History
                {{-- <a href="{{ route('admin.company.create') }}" class="btn btn-danger"><i
                        class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            {{-- <form action="" method="GET">
                <div class="row py-2">
                    <div class="col-12 col-md-3">
                        <input type="date" name="s_date" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="date" name="e_date" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="text" name="employee">
                    </div>
                    <div class="col-12 col-md-3">
                        <input type="submit" class="btn btn-info" value="Filter">
                    </div>
                </div>
            </form>
            <div class="row py-2">
                <div class="col-12 col-md-3">
                    <input type="text" name="employee">
                </div>
            </div> --}}
            @include('admin.attendance.ajax.attendanceList')

        </div>
    </div>
@endsection



@push('js')
@endpush
