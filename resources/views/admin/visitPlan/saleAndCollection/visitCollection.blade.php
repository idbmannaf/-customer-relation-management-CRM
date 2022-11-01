@extends('admin.layouts.adminMaster')
@push('title')
    admin Dashboard |Visit Sales
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
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
        <div class="card-header">
            <strong> Collecttions</strong>
        </div>

        <div class="card-body">
            <form action="" method="GET" class="pb-1">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <select name="time" id="time" class="form-control select2">
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
                    <div class="form-group col-12 col-md-3" id="userField">
                        <select id="employee" name="employee"
                            class="form-control user-select select2-container employee-select select2"
                            data-placeholder="Employee Id / Name" data-ajax-url="{{ route('admin.employeesAllAjax') }}"
                            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200" style="">
                            @if (isset($input['employee']))
                                <option selected value="{{ $employee->id }}">{{ $employee->name }}
                                    ({{ $employee->employee_id }})
                                </option>
                            @endif
                        </select>
                    </div>
                    {{-- <div class="col-12 col-md-3">
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
                    </div> --}}
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
            <div class="float-right">
                <a href="" onclick="return printDiv('printArea');" class="btn btn-xs btn-primary"><i
                        class="fa fa-print"></i>
                    Print</a> <a class="btn btn-warning btn-sm  m-2"
                    onclick="exportTableToCSV('collections-{{ isset($input['time']) ? strtolower($input['time']).'-' : '' }}{{ isset($input['employee']) ? $employee->employee_id : '' }}-{{date('Y-m-d')}}.csv')"><i
                        class="fas fa-file-csv"></i> Export</a>
            </div>

            <div class="table-responsive " id="printArea">
                <table class="table table-borderd table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>#SL</th>
                            <th>Visit Id</th>
                            <th>Visit Date</th>
                            <th>Previous Collection Amount</th>
                            <th>Moved Collection Amount</th>
                            <th>Current Ladger Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visit_collection as $collection)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $collection->id }}</td>
                                <td>{{ $collection->date_time }}</td>
                                <td>{{ $collection->prev_collection_amount }}</td>
                                <td>{{ $collection->moved_collection_amount }}</td>
                                <td>{{ $collection->current_collection_ledger_balance }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection



@push('js')
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            $('.employee-select').select2({
                theme: 'bootstrap4',
                // minimumInputLength: 1,
                placeholder: 'Search Employee Id/Name',
                ajax: {
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        // alert(data[0].s);
                        var data = $.map(data, function(obj) {
                            obj.id = obj.id || obj.id;
                            return obj;
                        });
                        var data = $.map(data, function(obj) {
                            obj.text = obj.name + "(" + obj.employee_id + ")";
                            return obj;
                        });
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                },
            });


        });
    </script>
    <script>
        function exportTableToExcel(tableID, filename = '') {
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            // Specify file name
            filename = filename ? filename + '.xls' : 'user_registration_data.xls';
            // Create download link element
            downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);
            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                // Setting the file name
                downloadLink.download = filename;
                //triggering the function
                downloadLink.click();
            }
        }

        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;
            // CSV file
            csvFile = new Blob([csv], {
                type: "text/csv"
            });
            // Download link
            downloadLink = document.createElement("a");
            // File name
            downloadLink.download = filename;
            // Create a link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);
            // Hide download link
            downloadLink.style.display = "none";
            // Add the link to DOM
            document.body.appendChild(downloadLink);
            // Click download link
            downloadLink.click();
        }

        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll("table tr");
            for (var i = 0; i < rows.length; i++) {
                var row = [],
                    cols = rows[i].querySelectorAll("td, th");
                for (var j = 0; j < cols.length; j++)
                    row.push("\"" + cols[j].innerText + "\"");
                csv.push(row.join(","));
            }
            // Download CSV file
            downloadCSV(csv.join("\n"), filename);
        }
    </script>
@endpush
