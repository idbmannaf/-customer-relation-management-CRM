@extends('employee.layouts.employeeMaster')
@push('title')
  Employee Dashboard | Reffered Service Call
@endpush

@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Reffered Service Call
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm ">
                    <thead>
                        <tr>
                            <th>Date Time</th>
                            <th>Action</th>
                            @if ($employee->team_admin)
                                <th>Employee</th>
                            @endif
                            <th>Customer</th>
                            <th>Customer Location</th>
                            <th>Purpose of visit</th>
                            <th>Admin Note</th>
                            <th>Approved At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($calls as $call)
                            <tr>
                                <td>{{ $call->date_time }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        {{-- @if (!$call->approved_at)
                                                <a class="dropdown-item"
                                                    href="{{route('employee.updateCalls',$call)}}">Edit</a>
                                        @endif --}}
                                        {{-- @if ($employee->team_admin) --}}
                                        <a class="dropdown-item"
                                        href="{{route('employee.callWiseVisitPlan',$call)}}">Visit Plan</a>
                                        {{-- @endif --}}


                                    </div>
                                </td>
                                @if ($employee->team_admin)
                                    <td>{{ $call->employee ? $call->employee->name : 'No Employee Selected' }}</td>
                                @endif
                                <td>{{ $call->customer->customer_name }}{{ $call->customer->customer_name }} ({{ $call->customer->customer_code }})</td>
                                <td>{{ $call->customer_office ? $call->customer_office->title : $call->customer_address }}</td>
                                <td>{{ $call->purpose_of_visit }}</td>
                                <td>{{ $call->admin_note }}</td>
                                <td>{{ $call->approved_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
{{$calls->render()}}
    </div>
@endsection



@push('js')
@endpush
