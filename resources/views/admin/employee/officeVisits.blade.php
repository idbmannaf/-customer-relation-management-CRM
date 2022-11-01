@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard |  Office Visit Dates
@endpush

@push('css')
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <div class="card-title">Office Visits Of Employee: {{$employee->employee_id}}</div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderd table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($office_visit_dates as $office_visit_date)
                        <tr>
                            <td>
                                <a href="{{route('admin.user.employeeOfficeVisitsDetails',['employee'=>$employee,'date'=>$office_visit_date->date])}}">{{$office_visit_date->date}}</a>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <th colspan="2" class="text-danger"> No Office Vistis Found</th>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{$office_visit_dates->render()}}
        </div>
    </div>


    @endsection



@push('js')
@endpush
