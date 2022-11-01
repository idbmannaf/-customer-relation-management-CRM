@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Convayances Bill
@endpush
@push('css')
    <style>
        span.rq_title {
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            background-color: gray;
            padding: 10px;
            border-radius: 20px;
        }

        input.custom.form-control {
            border: none;
            border-bottom: 1px solid;
            border-bottom-style: dotted;
            outline: none;
            /* text-align: center; */
        }

        input.custom.form-control:focus-visible {
            border: none !important;
            border-bottom: 1px solid !important;
            border-bottom-style: dotted !important;

            outline: none;
        }

        .item_bottom {
            display: flex;
            align-items: self-end;
            margin-top: 20px;
            font-weight: 500 !important;
        }

        .flex-auto {
            flex: auto;
            width: 120px;
        }

        .border-bottom {
            border-bottom: 1px solid !important;
            border-bottom-style: dotted !important;
        }
    </style>
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Convayances Of Visit {{ $visit->date_time }}
                <a href="{{ route('employee.customerVisits', ['visit_plan' => $visit->visit_plan]) }}"
                    class="btn btn-danger btn-sm">Back</a>
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-112">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="customer col-12 col-md-4">
                                    <fieldset>
                                        <legend>Customer</legend>
                                        <b>Name: </b> {{ $convayances->customer->customer_name }}
                                        ({{ $convayances->customer->id }}) <br>
                                        <b>Code: </b> {{ $convayances->customer->customer_code }}<br>
                                        <b>Mobile: </b> {{ $convayances->customer->mobile }}
                                        ({{ $convayances->customer->id }}) <br>
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

            {{-- @if ($visit->employee->id == auth()->user()->employee->id)
                @include('employee.visitPlan.visit.convayances.part.teamAdminOwn') --}}
            @if (auth()->user()->employee->team_admin ||
                auth()->user()->employee->company->access_all_call_visit_plan_without_call)
                @include('employee.visitPlan.visit.convayances.part.teamAdmin')
            @else
                @include('employee.visitPlan.visit.convayances.part.teamMember')
            @endif


        </div>
    </div>
@endsection

@push('js')

    <script>
        $(document).on('click', '.addCon', function(e) {
            e.preventDefault();
            var that = $(this);
            var movement_details = that.closest('tr').find('.movement_details').val();
            var travel_mode = that.closest('tr').find('.travel_mode').val();
            var amount = that.closest('tr').find('.amount').val();
            var start_time = that.closest('tr').find('.start_time').val();
            var end_time = that.closest('tr').find('.end_time').val();
            var start_from = that.closest('tr').find('.start_from').val();
            var start_to = that.closest('tr').find('.start_to').val();

            //Condition Start

            if (start_time == '') {
                that.closest('tr').find('.start_time').addClass('is-invalid');
                return;
            }else{
                that.closest('tr').find('.start_time').removeClass('is-invalid');
            }
            if (end_time == '') {
                that.closest('tr').find('.end_time').addClass('is-invalid');
                return;
            }else{
                that.closest('tr').find('.end_time').removeClass('is-invalid');
            }
            if (start_from == '') {
                that.closest('tr').find('.start_from').addClass('is-invalid');
                return;
            }else{
                that.closest('tr').find('.start_from').removeClass('is-invalid');
            }
            if (start_to == '') {
                that.closest('tr').find('.start_to').addClass('is-invalid');
                return;
            }else{
                that.closest('tr').find('.start_to').removeClass('is-invalid');
            }
            if (travel_mode == '') {
                that.closest('tr').find('.travel_mode').addClass('is-invalid');
                return;
            }else{
                that.closest('tr').find('.travel_mode').removeClass('is-invalid');
            }
            if (amount == '' || amount == 0) {
                that.closest('tr').find('.amount').addClass('is-invalid');
                return;
            }else{
                that.closest('tr').find('.amount').removeClass('is-invalid');
            }
            // that.closest('tr').find('.travel_mode').removeClass('is-invalid');
            // that.closest('tr').find('.amount').removeClass('is-invalid');
            // that.closest('tr').find('.start_time').removeClass('is-invalid');
            // that.closest('tr').find('.end_time').removeClass('is-invalid');
            // that.closest('tr').find('.start_from').removeClass('is-invalid');
            // that.closest('tr').find('.start_to').removeClass('is-invalid');
            //Condition End
            var url = that.attr('data-url');
            $.ajax({
                url: url,
                method: "GET",
                data: {
                    movement_details: movement_details,
                    travel_mode: travel_mode,
                    amount: amount,
                    start_time: start_time,
                    end_time: end_time,
                    start_from: start_from,
                    start_to: start_to
                },
                success: function(res) {
                    if (res.success) {
                        $('.total_amount').text(res.total_amount);
                        that.closest('tbody').append(res.html);

                        //empty Form Value Start
                        that.closest('tr').find('.movement_details').val('');
                        that.closest('tr').find('.travel_mode').val('');
                        that.closest('tr').find('.amount').val(0);
                        that.closest('tr').find('.start_time').val('');
                        that.closest('tr').find('.end_time').val('');
                        that.closest('tr').find('.start_from').val('');
                        that.closest('tr').find('.start_to').val('');
                        //empty Form Value End
                    } else {

                        console.log('Data Not Found');
                    }
                }
            });

        })
        $(document).on('click', '.removeCon', function(e) {
            e.preventDefault();
            var that = $(this);
            var item_id = that.attr('data-id');
            var url = that.attr('data-url');
            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    if (res.success) {
                        that.closest('tr').remove();
                        $('.total_amount').text(res.total_amount);
                    } else {
                        $(".showError").html(res.messge);
                        console.log('Data Not Found');
                    }
                }
            });

        })

        function update(e, type) {
            var that = $(e);
            var url = that.closest('tr').find('.update_url').val();
            $.ajax({
                url: url,
                method: "GET",
                data: {
                    type: type,
                    value: that.val()
                },
                success: function(res) {
                    if (res.success) {
                        $('.total_amount').text(res.total_amount);
                    }
                }
            });
        }
        $(document).on('input', '.movement_details_ajax', function(e) {
            var that = $(this);
            var item_id = that.closest('.temp').attr('data-id');
            var url = that.closest('.temp').attr('data-url');
            var finalUrl = url + "?type=movement_details";
            $.ajax({
                url: finalUrl,
                method: "GET",
                data: {
                    value: that.val()
                },
                success: function(res) {
                    if (res.success) {
                        $('.total_amount').text(res.total_amount);
                    }
                }
            });

        })
        $(document).on('change', '.travel_mode_ajax', function(e) {
            var that = $(this);
            var item_id = that.closest('.temp').attr('data-id');
            var url = that.closest('.temp').attr('data-url');
            var finalUrl = url + "?type=travel_mode";
            $.ajax({
                url: finalUrl,
                method: "GET",
                data: {
                    value: that.val()
                },
                success: function(res) {
                    if (res.success) {
                        $('.total_amount').text(res.total_amount);
                    }
                }
            });

        })

        $(document).on('input', '.amount_ajax', function(e) {
            var that = $(this);
            var item_id = that.closest('.temp').attr('data-id');
            var url = that.closest('.temp').attr('data-url');
            var finalUrl = url + "?type=amount";
            if (that.val() <= 0) {
                that.closest('.temp').find('.removeCon').trigger('click');
                return;
            }
            $.ajax({
                url: finalUrl,
                method: "GET",
                data: {
                    value: that.val()
                },
                success: function(res) {
                    if (res.success) {
                        $('.total_amount').text(res.total_amount);
                    }
                }
            });

        })
    </script>
@endpush
