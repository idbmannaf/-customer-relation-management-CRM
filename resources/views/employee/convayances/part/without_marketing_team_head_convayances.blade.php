<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div> Claim Details For Convayances Bill
                <a href="" onclick="return printDiv('printArea');"
                class=" pull-right btn btn-xs btn-primary print  removePrint">Print</a></div>
            <div>
                <b>Payemnt Status: </b>
                @if ($convayance->paid)
                    <span class="badge badge-success">Paid</span>
                @else
                    <span class="badge badge-danger">Unpaid</span>
                @endif
            </div>
            <div><b>Status: </b>
                @if ($convayance->status == 'pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif ($convayance->status == 'rejected')
                    <span class="badge badge-danger">Rejected</span>
                @elseif ($convayance->status == 'approved')
                    <span class="badge badge-success">Approved</span>
                @endif
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
        }
    </script>
    <div class="card-body" id="printArea">
        <div class="con_details">
            <div class="row">
                <div class="col-md-4 @if (Agent::isMobile()) order-3 @endif ">
                    <h2 class="font-weight-bold">Orient Bd Limited</h2>
                    Concord Tower (13th Floor), Suit No. 1401 <br>
                    113 Kazi Nazrul Islam Avenue,Dhaka 1205
                </div>
                <div class="col-md-5  @if (Agent::isMobile()) order-2 pt-2 @endif  text-center">
                    <span class="rq_title" style="vertical-align: center">CONVEYANCE BILL</span>
                </div>
                <div class="col-md-3 @if (Agent::isMobile()) order-1 @endif">
                    <div
                        class="d-flex @if (Agent::isDesktop()) justify-content-end @else justify-content-center pb-2 @endif">
                        <img style="width: 150px;" src="{{ asset('img/orient.png') }}" alt="" srcset="">
                    </div>
                </div>
            </div>
            <br>
            <div class="row pb-1">
                <div class="col-12 col-md-6">
                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                        <label for="party_name" class="">Employee's Name: </label>
                        <div class=" pl-2">
                            {{ $visit->employee->name }} ({{ $visit->employee->employee_id }})
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                        <label for="party_name" class="">Degignation: </label>
                        <div class=" pl-2">
                            {{ $visit->employee->designation ? $visit->employee->designation->title : '' }}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                        <label for="date" class="">Date:</label>
                        <div class=" pl-2">
                            {{-- {{$visit->visit_plan->visit_start_at}} --}}
                            {{ \Carbon\Carbon::parse($visit->visit_plan->visit_start_at)->format('Y-m-d') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">

                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                        <label for="date" class="">Complain/Challan No:</label>
                        <div class=" pl-2">
                            {{ $visit->id }}
                        </div>

                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                        <label for="date" class="">Project Name & Location: <span class=" pl-2 font-weight-normal">
                            {{$visit->customer ? $visit->customer->customer_name : ''}}-{{ $visit->visit_plan->service_address }}
                        </span></label>
                    </div>
                </div>
            </div>
            <div class="showError text-danger">

            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm text-nowarp">
            <thead style="background-color: gray">
                <tr>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Start From</th>
                    <th>To</th>
                    <th>Type</th>
                    <th>Purpose Of Travel</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($convayance->items as $item)
                    <tr>
                        <td> {{ $item->start_time }}</td>
                        <td> {{ $item->end_time }}</td>
                        <td> {{ $item->start_from }}</td>
                        <td> {{ $item->start_to }}</td>
                        <td> {{ $item->travel_mode }}</td>
                        <td> {{ $item->movement_details }}</td>
                        <td> {{ $item->amount }}</td>
                    </tr>
                @endforeach

            <tfoot>
                <tr>
                    <th colspan="6" class="text-right">Total Amount</th>
                    <th>{{ $convayance->total_amount }}</th>
                </tr>
            </tfoot>
            </tbody>
        </table>
    </div>



</div>
