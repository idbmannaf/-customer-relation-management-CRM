<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between flex-wrap">
            <div> Claim Details For Convayances Bill <a href="" onclick="return printDiv('printArea');"
                    class=" pull-right btn btn-xs btn-primary print  removePrint">Print</a> </div>
            <div>
                <b>Payment Status: </b>
                @if ($convayances->paid)
                    <span class="badge badge-success">Paid</span>
                @else
                    <span class="badge badge-danger">Unpaid</span>
                @endif
            </div>
            <div><b>Status: </b>
                @if ($convayances->status == 'pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif ($convayances->status == 'rejected')
                    <span class="badge badge-danger">Rejected</span>
                @elseif ($convayances->status == 'approved')
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
                <div class="col-md-4 @if(Agent::isMobile()) order-3 @endif ">
                    <h2 class="font-weight-bold">Orient Bd Limited</h2>
                    Concord Tower (13th Floor), Suit No. 1401 <br>
                    113 Kazi Nazrul Islam Avenue,Dhaka 1205
                </div>
                <div class="col-md-5  @if(Agent::isMobile()) order-2 @endif  text-center @if (Agent::isDesktop()) @else  pt-2 @endif">
                    <span class="rq_title" style="vertical-align: center">CONVEYANCE BILL</span>
                </div>
                <div class="col-md-3 @if(Agent::isMobile()) order-1 @endif  ">
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
                        <label for="date" class="">Project Name & Location:</label>
                        <div class=" pl-2">
                            {{$visit->customer ? $visit->customer->customer_name : ''}}-{{ $visit->visit_plan->service_address }}
                        </div>

                    </div>
                </div>
            </div>
            <div class="showError text-danger">

            </div>
        </div>

        @if ($convayances->status == 'temp' || $convayances->status == 'pending')
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowarp">
                    @if (Agent::isDesktop())
                        <thead style="background-color: gray">
                            <tr>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Start From</th>
                                <th>To</th>
                                <th>Type</th>
                                <th>Purpose Of Travel</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    @else
                        <style>

                            tbody td {
                                width: 126%;
                                display: block;
                            }
                            .card-body{
                                padding: 0.5rem !important;
                            }

                            tr.ifMobile {
                                width: 100% !important;
                                margin-bottom: 40px !important;
                                display: block;
                            }
                        </style>
                    @endif

                    <tbody>
                        <tr class=" @if(Agent::isMobile()) ifMobile @endif">
                            <td><input type="time" name="start_time" placeholder="Start Time"
                                    class="form-control start_time" id="start_time">
                            </td>
                            <td><input type="time" name="end_time"
                                    placeholder="End Time"class="form-control end_time" id="end_time">
                            </td>
                            <td><input type="text" name="start_from" placeholder="Start From"
                                    class="form-control start_from" id="start_from">
                            </td>
                            <td><input type="text" name="start_to" placeholder="Start To"
                                    class="form-control start_to" id="start_to">
                            </td>
                            <td>
                                <select name="travel_mode" id="travel_mode" class="form-control  travel_mode">
                                    <option value="">Select Travel Mode</option>
                                    <option value="Rickshaw">Rickshaw</option>
                                    <option value="Auto Rickshaw">Auto Rickshaw</option>
                                    <option value="CNG">CNG </option>
                                    <option value="Bus">Bus </option>
                                    <option value="Motocycle"> Motocycle</option>

                                </select>
                            </td>
                            <td> <input type="text" name="movement_details" class="form-control movement_details "
                                    placeholder="Movement Details"></td>
                            <td>
                                <input type="number" name="amount" class="form-control amount" placeholder="0.00">
                            </td>
                            <td><button type="button"
                                    data-url="{{ route('employee.convayances.add', ['convayances' => $convayances, 'visit' => $visit]) }}"
                                    class="btn btn-success btn-sm addCon"><i class="fas fa-plus"></i></button></td>
                        </tr>
                        @foreach ($convayances->items as $item)
                            <tr class=" @if(Agent::isMobile()) ifMobile @endif">
                                <td><input type="time" name="start_time" oninput="update(this,'start_time')"
                                        value="{{ $item->start_time }}" class="form-control" id="start_time">
                                    <input type="hidden" class="update_url"
                                        value="{{ route('employee.convayancesChangeAjax', ['convayances' => $convayances->id, 'item' => $item->id]) }}">
                                </td>
                                <td><input type="time" name="end_time" oninput="update(this,'end_time')"
                                        value="{{ $item->end_time }}" class="form-control" id="end_time">
                                </td>
                                <td><input type="text" name="start_from" oninput="update(this,'start_from')"
                                        value="{{ $item->start_from }}" class="form-control" id="start_from">
                                </td>
                                <td><input type="text" name="start_to" oninput="update(this,'start_to')"
                                        value="{{ $item->start_to }}" class="form-control" id="start_to"></td>
                                <td>
                                    <select name="travel_mode" id="travel_mode" class="form-control "
                                        onchange="update(this,'travel_mode')">
                                        <option value="">Select Travel Mode</option>
                                        <option {{ $item->travel_mode == 'Rickshaw' ? 'selected' : '' }}
                                            value="Rickshaw">Rickshaw
                                        </option>
                                        <option {{ $item->travel_mode == 'Auto Rickshaw' ? 'selected' : '' }}
                                            value="Auto Rickshaw">Auto
                                            Rickshaw</option>
                                        <option {{ $item->travel_mode == 'CNG' ? 'selected' : '' }} value="CNG">CNG
                                        </option>
                                        <option {{ $item->travel_mode == 'Bus' ? 'selected' : '' }} value="Bus">Bus
                                        </option>
                                        <option {{ $item->travel_mode == 'Motocycle' ? 'selected' : '' }}
                                            value="Motocycle">Motocycle
                                        </option>

                                    </select>
                                </td>
                                <td> <input type="text" name="movement_details" class="form-control"
                                        oninput="update(this,'movement_details')" placeholder="Movement Details"
                                        value="{{ $item->movement_details }}">
                                </td>
                                <td>
                                    <input type="number" name="amount" oninput="update(this,'amount')"
                                        value="{{ $item->amount }}" class="form-control  " placeholder="0.00">
                                </td>

                                <td>
                                    <button type="button" data-id="{{ $item->id }}"
                                        data-url="{{ route('employee.convayances.delete', ['convayances' => $convayances->id, 'item' => $item->id]) }}"
                                        class="btn btn-danger btn-sm removeCon"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach

                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-right">Total Amount</th>
                            <th colspan="2" class="total_amount">{{ $convayances->total_amount }}</th>
                        </tr>
                    </tfoot>
                    </tbody>
                </table>
            </div>
            <div class="submit">
                <form
                    action="{{ route('employee.convayancesSubmit', ['convayances' => $convayances, 'visit' => $visit]) }}">
                    <input type="submit" class="btn btn-info" name="submit" value="pending"
                        onclick="return confirm('Are you Sure? you want to submit this convayances bill?')">
                </form>
            </div>
        @else
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
                        @foreach ($convayances->items as $item)
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
                            <th>{{ $convayances->total_amount }}</th>
                        </tr>
                    </tfoot>
                    </tbody>
                </table>
            </div>
        @endif
        <div class="d-flex justify-content-between flex-wrap">
            <div class="text-center font-width-bold">
                <div>{{ $visit->employee->name }}</div>
                <div>Prepared By</div>
            </div>

            <div class="text-center font-width-bold">
                <div>Reviewd By</div>
                <div>Departmental Head</div>
            </div>

            <div class="text-center font-width-bold">
                <div>Checked By</div>
                <div>
                    Account Department
                </div>
            </div>
            <div class="text-center font-width-bold">
                <div>Approved</div>

            </div>
        </div>
    </div>

</div>
