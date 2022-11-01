@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Convayances Bill
@endpush

<style>
     span.rq_title {
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            background-color: gray;
            padding: 10px;
            border-radius: 20px;
        }

</style>
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Convayances Of ID: {{ $convayance->id }}
            </div>
        </div>
        @php
            $visit = $convayance->visit;
            $visit_plan = $convayance->visit_plan;
        @endphp
        @include('alerts.alerts')
        <script type="text/javascript">
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
            }
        </script>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-112">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="customer col-12 col-md-4">
                                    <fieldset>
                                        <legend>Customer</legend>
                                        <b>Name: </b> {{ $convayance->customer->customer_name }}
                                        ({{ $convayance->customer->id }}) <br>
                                        <b>Code: </b> {{ $convayance->customer->customer_code }}<br>
                                        <b>Mobile: </b> {{ $convayance->customer->mobile }}
                                        ({{ $convayance->customer->id }}) <br>
                                        @if ($visit->visit_plan->office)
                                            <b>Location Name: </b> {{ $visit->visit_plan->office->title }} :
                                            {{ $visit->visit_plan->office->customer_company->name }}<br>
                                        @endif
                                    </fieldset>
                                </div>
                                @if (auth()->user()->employee->team_admin)
                                    <div class="aboutcon col-12 col-md-5">
                                        <fieldset>
                                            <legend>Visit & Visit Plan & Employee</legend>
                                            <b>Visit_plan id: : </b> {{ $visit->visit_plan->id }} <br>
                                            <b>Visit id: : </b> {{ $visit->id }} <br>
                                            <b>Employee: </b> {{ $visit->employee->name }}
                                            ({{ $visit->employee->employee_id }}) <br>
                                        </fieldset>
                                    </div>

                                    <div class="employee col-12 col-md-3">
                                        <div class="card">
                                            <a href="{{ route('employee.emplyeeDetailsAboutMovement', ['visit' => $visit, 'type' => 'location']) }}"
                                                class="card-body bg-success shadow">Employee Location</a>
                                        </div>
                                        <div class="card">
                                            <a href="{{ route('employee.emplyeeDetailsAboutMovement', ['visit' => $visit, 'type' => 'visit']) }}"
                                                class="card-body bg-info shadow">Employee Visits</a>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">

                <div class="d-flex justify-content-between">
                    <div> Claim Details For Convayances Bill <a href="" onclick="return printDiv('printArea');"
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
                                    <label for="date" class="">Project Name & Location:</label>
                                    <div class=" pl-2">
                                        {{ $visit->visit_plan->service_address }}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="showError text-danger">

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
                                    {{-- <th>Purpose Of Travel</th> --}}
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
                                        {{-- <td> {{ $item->movement_details }}</td> --}}
                                        <td> {{ $item->amount }}</td>
                                    </tr>
                                @endforeach

                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Total Amount</th>
                                    <th>{{ $convayance->total_amount }}</th>
                                </tr>
                            </tfoot>
                            </tbody>
                        </table>
                    </div>
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
                    @if (!$convayance->paid)
                        <div class="row">
                            <div class="col-12 col-md-3 m-auto pt-3">
                                @if (!$convayance->paid)
                                <form action="{{ route('employee.convayancesBillPaid', $convayance) }}" method="POST">
                                    @csrf
                                    <input type="submit" value="Paid" class="btn btn-success">
                                </form>
                            @endif
                            </div>
                        </div>
                    @endif
                </div>

            </div>


        </div>
    </div>
@endsection
