@extends('admin.layouts.adminMaster')
@push('title')
    Emplyee Dashboard |Call Wise Visit Plan
@endpush

@push('css')
    <link href="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ asset('https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> Service Call Wise Assign to (Visit Plans)
                @can('visit-plan')
                    <a href="{{ route('admin.addVisitPlan', $call) }}" class="btn btn-danger btn-sm">New Visit Plan</a>
                @endcan
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 ">
                            <h5>Call Details</h5>
                            <b>Call Id: </b> {{ $call->id }} <br>
                            <b>Customer name: </b> {{ $call->customer->customer_name }}
                            ({{ $call->customer->customer_code }}) <br>
                            <b>Customer Address/Location: </b>
                            {{ $call->customer_office ? $call->customer_office->title : $call->customer_address }} <br>
                            <b>{{ ucfirst($call->type) }} Address: </b> {{ $call->service_address }} <br>
                            {{-- <b>Customer Employee: </b> {{ $call->customer->employee ? $call->customer->employee->name : '' }} <br> --}}
                            <b>Purpose Of Visit: </b> {{ $call->purpose_of_visit }} <br>
                            <b>Admin Note: </b> {{ $call->admin_note }} <br>
                            <b>Date & Time: </b> {{ $call->date_time }} <br>
                            @if ($call->customer && $call->customer->user && $call->customer->user->username)
                                <a class="btn btn-success btn-xs" href="tel:{{ $call->customer->user->username }}"><i
                                        class="fas fa-envelope"></i>{{ $call->customer->user->username }}</a> <br>
                            @endif
                            @if ($call->customer && $call->customer->mobile)
                                <a class="btn btn-warning btn-xs" href="tel:{{ $call->customer->mobile }}"><i
                                        class="fas fa-phone-volume"></i>{{ $call->customer->mobile }}</a> <br>
                            @endif
                            @if ($call->employee)
                                <b>Employee: </b> {{ $call->employee->name }} ({{ $call->employee->employee_id }})<br>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>Serial No</th>
                            <th>Action</th>
                            <th>Employee</th>
                            <th>Date Time</th>
                            <th>Purpose Of Visit</th>
                            {{-- <th>Payment Collection Date</th>
                            <th>Payment Maturity Date</th> --}}
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visit_plans as $visit_plan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>

                                        @if (auth()->user()->can('visit') || auth()->user()->can('visit-add') || auth()->user()->can('visit-update'))
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.visits', ['visitPlan' => $visit_plan]) }}">Visits</a>

                                        </div>
                                        @endif


                                    </div>
                                </td>
                                <td>{{ $visit_plan->employee->name }} ({{ $visit_plan->employee->employee_code }})</td>
                                <td>{{ $visit_plan->date_time }}</td>
                                <td>{{ $visit_plan->purpose_of_visit }}</td>
                                {{-- <td>{{ $visit_plan->payment_collection_date }}</td>
                                <td>{{ $visit_plan->payment_maturity_date }}</td> --}}
                                <td>
                                    @if ($visit_plan->team_admin_approved_at)
                                        Approved: {{ $visit_plan->team_admin_approved_at }}
                                    @elseif ($visit_plan->team_admin_approved_at)
                                        <a href="{{ route('employee.customerVisitPlanStatusUpdate', ['status' => 'approved', 'type' => $type, 'visit' => $visit_plan]) }}"
                                            onclick="return confirm('are you Sure You Want To Approve?')"
                                            class="btn btn-sm btn-success">Approved</a>
                                    @else
                                        Pending
                                    @endif


                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection


@push('js')
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js') }}"></script>

    <script>
        // $('.select2').select2();
        $("#employee").change(function() {
            var that = $(this);
            var url = that.attr('data-url');
            var finalUrl = url + "?employee=" + that.val();
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    console.log(res.success);
                    if (res.success) {
                        $("#customer").html('');
                        $.each(res.customer, function(i, item) {
                            $("#customer").append(
                                "<option value='" + item.id + "'>" + item.customer_name +
                                "</option>");
                        });
                        $("#customer").trigger('change');
                    } else {
                        $("#customer").html(' ');
                    }

                }
            })
        });
        $("#customer").change(function() {
            var that = $(this);
            var url = that.attr('data-url');
            var finalUrl = url + "?customer=" + that.val();
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    console.log(res.success);
                    if (res.success) {
                        $("#customer_office_location").html(res.html);
                    } else {
                        $("#customer_office_location").html(' ');
                    }

                }
            })
        });
    </script>
@endpush
