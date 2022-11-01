<div>
    <button class="btn btn-warning btn-xs " onclick="exportTableToCSV('attandance.csv')"><i class="las la-file-alt aiz-side-nav-icon"></i> Export</button> | <a href="" onclick="return printDiv('printArea');"
    class=" pull-right btn btn-xs btn-primary print  removePrint">Print</a>
</div>

<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
    }
</script>


<div class="table-responsive" id="printArea">
    <table class="table table-bordered table-sm">
        <thead>
            <th>Sl</th>
            <th>Employee Id</th>
            <th>Employee Name</th>
            <th>Company</th>
            <th>Office Location</th>
            <th>Present Type</th>
            <th>Entry Time</th>
            <th>Exit Time</th>
            <th>Duration</th>
            <th>Status</th>

        </thead>
        <tbody>
            <?php $i = 1;
            $i = ($employees->currentPage() - 1) * $employees->perPage() + 1;
            ?>
            @foreach ($employees as $employee)
                {{-- {{dd($attendance->office->office_start_time)}} --}}
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $employee->id }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->company->name }}</td>
                    <td>{{ $employee->user->officeLocation->title }}</td>
                    <td>
                        @if ($employee->user->todayAttendance($input['date'], $employee->user_id))
                            <span class="text-success">Present</span>
                        @else
                            <span class="text-danger">Absent</span>
                        @endif
                    </td>
                    <td>
                        @if ($attendance = $employee->user->todayAttendance($input['date'], $employee->user_id))
                            {{ $attendance->logged_in_at }}
                        @endif
                    </td>
                    <td>
                        @if ($attendance = $employee->user->todayAttendance($input['date'], $employee->user_id))
                            {{ $attendance->logged_out_at }}
                        @endif
                    </td>
                    <td>
                        @if ($attendance = $employee->user->todayAttendance($input['date'], $employee->user_id))
                        {{timestamToTimeDiffarece($attendance->logged_in_at,$attendance->logged_out_at )}}
                        @endif
                    </td>
                    <td>
                        @if ($attendance = $employee->user->todayAttendance($input['date'], $employee->user_id))
                            @if ($attendance->office_location_id && $attendance->office->office_start_time < \Carbon\Carbon::parse($attendance->logged_in_at)->format('H:m:s'))
                                <span class="text-danger">Late Entry</span>
                            @else
                                <span class="text-success">Present</span>
                            @endif
                        @endif

                    </td>
                </tr>
                <?php $i++; ?>
            @endforeach
        </tbody>
    </table>
</div>

{{ $employees->render() }}


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
