<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead>
            <th>Sl</th>
            <th>Employee Id</th>
            <th>Employee Name</th>
            <th>Entry Time</th>
            <th>Exit Time</th>
            <th>Duration</th>
            <th>Status</th>
            <th>Company</th>
            <th>Office Location</th>
        </thead>
        <tbody>
            <?php $i = 1;
            $i = ($attendances->currentPage() - 1) * $attendances->perPage() + 1;
            ?>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $attendance->user->username }}</td>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->logged_in_at }}</td>
                    <td>{{ $attendance->logged_out_at }}</td>
                    <td>{{ timestamToTimeDiffarece($attendance->logged_in_at, $attendance->logged_out_at) }}</td>
                    <td>
                        @if ($attendance->status == 'present')
                        <span class="badge badge-success">Present</span>
                    @elseif($attendance->status == 'late_entry')
                        <span class="badge badge-warning">Late Entry</span>
                    @else
                        <span class="badge badge-danger">Absent</span>
                    @endif
                    </td>

                    <td>{{ $attendance->company->name }}</td>
                    <td>{{ $attendance->office? $attendance->office->title : '' }}</td>
                </tr>
                <?php $i++; ?>
            @endforeach
        </tbody>
    </table>
</div>
{{$attendances->render(0)}}
