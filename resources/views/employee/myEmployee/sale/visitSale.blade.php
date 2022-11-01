@extends('employee.layouts.employeeMaster')
@push('title')
    Employee Dashboard |Visit Sales
@endpush

@push('css')
{{-- <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}"> --}}
<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
    }
</script>
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <strong> Sales of {{ $employee->name }} ({{ $employee->employee_id }})</strong>
        </div>
        <div class="card-body">
            <form action="" method="GET" class="pb-1">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <select name="time" id="time" class="form-control">
                            <option value="">Select Type</option>
                            <option {{ isset($input['time']) && $input['time'] == 'today' ? 'selected' : '' }}
                                value="today">Today</option>
                            <option {{ isset($input['time']) && $input['time'] == 'yesterday' ? 'selected' : '' }}
                                value="yesterday">Yesterday</option>
                            <option {{ isset($input['time']) && $input['time'] == 'last_7_days' ? 'selected' : '' }}
                                value="last_7_days">Last 7 Days</option>
                            <option {{ isset($input['time']) && $input['time'] == 'last_month' ? 'selected' : '' }}
                                value="last_month">Last Month</option>
                            {{-- <option {{ isset($input['time']) && $input['time'] == 'date_range' ? 'selected' : '' }}
                                value="date_range">Date Range</option> --}}
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="employee" id="" class="form-control">
                            <option value="">Select Employee</option>
                            @foreach ($my_employees as $myEmployee)
                                <option
                                    {{ isset($input['employee']) && $input['employee'] == $myEmployee->id ? 'selected' : '' }}
                                    value="{{ $myEmployee->id }}">{{ $myEmployee->name }}
                                    ({{ $myEmployee->employee_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-12 col-md-5 fromTo">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <input type="date" name="to" class="form-control" value="{{ $input ? $input['to'] : old('to') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="date" name="from" class="form-control" value="{{ $input ? $input['from'] : old('from') }}">
                            </div>

                        </div>
                    </div> --}}
                    <div class="col-12 col-md-1 submit">
                        <input type="submit" value="Filter" class="btn btn-info">
                    </div>
                </div>
            </form>
            <div class="container">
                @error('from')
            <div class="alert alert-danger">{{ $message }}</div>
           @enderror
           @error('to')
            <div class="alert alert-danger">{{ $message }}</div>
           @enderror
            </div>

            <a href="" onclick="return printDiv('printArea');" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Print</a>
            <div class="table-responsive" id="printArea">
                <table class="table table-borderd table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>#SL</th>
                            <th>Visit Id</th>
                            <th>Visit Date</th>
                            <th>Previous Sale Amount</th>
                            <th>Moved Sale Amount</th>
                            <th>Current Ladger Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visit_sales as $sale)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sale->id }}</td>
                                <td>{{ $sale->date_time }}</td>
                                <td>{{ $sale->prev_sale_amount }}</td>
                                <td>{{ $sale->moved_sale_amount }}</td>
                                <td>{{ $sale->current_sale_ledger_balance }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection



@push('js')
    {{-- <script src="{{ asset('js/select2.full.min.js') }}"></script> --}}
    <script>


        $(document).on('change', '#time', function(e) {
            var that = $(this);
            if (that.val() == 'date_range') {
                $(".fromTo").show();
            } else {
                $(".fromTo").hide();
            }
        })
        $(window).ready(function() {
            if ($("#time").val() == 'date_range') {
                $(".fromTo").show();
            } else {
                $(".fromTo").hide();
            }

        })
    </script>
@endpush
