@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Users Location
@endpush

@push('css')
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <div class="card-title">Attendance Of Employee: {{ $employee->employee_id }}</div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderd table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Company</th>
                            <th>Entry Time</th>
                            <th>Exit Time</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Head Office</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        $i = ($attendances->currentPage() - 1) * $attendances->perPage() + 1;
                        ?>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $attendance->user->officeLocation->company->name }}</td>
                                <td>{{ $attendance->logged_in_at }}</td>
                                <td>{{ $attendance->logged_out_at }}</td>
                                <td>{{ timestamToTimeDiffarece($attendance->logged_in_at, $attendance->logged_out_at) }}
                                </td>
                                <td>
                                    {{-- {{$attendance->user->officeLocation->office_start_time}} --}}
                                    @if ($attendance->user->officeLocation->office_start_time < \Carbon\Carbon::parse($attendance->logged_in_at)->format('H:m:s'))
                                        <span class="text-danger">Late Entry</span>
                                    @else
                                        <span class="text-success">Present</span>
                                    @endif

                                </td>
                                <td>{{ $attendance->user->officeLocation->title  }}</td>
                            </tr>
                            <?php $i++; ?>
                        @empty
                            <tr>
                                <th colspan="7" class="text-danger"> No Attandance Found</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $attendances->render() }}
        </div>
    </div>
@endsection



@push('js')
@endpush
