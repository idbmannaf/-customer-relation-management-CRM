@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Attendance
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">{{$status ?? 'All'}} Attendance ({{ date('Y-m-d') }})
                {{-- <a href="{{ route('admin.company.create') }}" class="btn btn-danger"><i
                        class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">

            {{-- <div class="table-responsive" id="printArea">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>Sl</th>
                        <th>Employee Id</th>
                        <th>Employee Name</th>
                        <th>Company</th>
                        <th>Office Location</th>
                        <th>Status</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Duration</th>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        $i = ($employees->currentPage() - 1) * $employees->perPage() + 1;
                        ?>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->company->name }}</td>
                                <td>{{ $employee->user->officeLocation->title }}</td>
                                <td>
                                    @if ($attandance = $employee->user->todayAttendance(\Carbon\Carbon::now()->format('Y-m-d'), $employee->user_id))
                                        @if ($attandance->status == 'present')
                                            <span class="text-success">Present</span>
                                        @elseif($attandance->status == 'late_entry')
                                            <span class="text-warning">Late Entry</span>
                                        @else
                                            <span class="text-danger">Absent</span>
                                        @endif
                                    @endif

                                </td>
                                <td>
                                    @if ($attendance = $employee->user->todayAttendance(\Carbon\Carbon::now()->format('Y-m-d'), $employee->user_id))
                                        {{ $attendance->logged_in_at }}
                                    @endif
                                </td>
                                <td>
                                    @if ($attendance = $employee->user->todayAttendance(\Carbon\Carbon::now()->format('Y-m-d'), $employee->user_id))
                                        {{ $attendance->logged_out_at }}
                                    @endif
                                </td>
                                <td>
                                    @if ($attendance = $employee->user->todayAttendance(\Carbon\Carbon::now()->format('Y-m-d'), $employee->user_id))
                                        {{ timestamToTimeDiffarece($attendance->logged_in_at, $attendance->logged_out_at) }}
                                    @endif
                                </td>

                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div> --}}
            @include('admin.attendance.ajax.attendanceList')



        </div>
    </div>
@endsection



@push('js')
@endpush
