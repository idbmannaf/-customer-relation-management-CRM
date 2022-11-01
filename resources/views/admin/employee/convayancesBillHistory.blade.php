@extends('admin.layouts.adminMaster')
@push('title')
    Convayances Bill History Of Employee: {{ $employee->employee_id }}
@endpush

@push('css')
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-info">Convayances Bill History Of Employee: {{$employee->name}} ({{ $employee->employee_id }})</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderd table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Paid Amount</th>
                        <th>Paid By</th>
                        <th>Purpose</th>
                        <th>Note</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    $i = ($histories->currentPage() - 1) * $histories->perPage() + 1;
                    ?>
                    @forelse ($histories as $history)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $history->paid_amount }}</td>
                            <td>{{ $history->created_at }}</td>
                            <td>{{ $history->paidBy ? $history->paidBy->name : '' }}</td>
                            <td>{{ $history->purpose }}</td>
                            <td>{{ $history->note }}</td>
                        </tr>
                        <?php $i++; ?>
                    @empty
                        <tr>
                            <th colspan="7" class="text-danger"> No Convayances Bill History Found</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $histories->render() }}
    </div>

    </div>
@endsection
