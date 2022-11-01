@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Attendance Report
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
    <style>
        .showBaseInEmployee {
            display: none;
        }

        li.select2-selection__choice {
            color: green !important;
        }

        span.select2-selection__choice__remove {
            color: red !important;
        }
    </style>
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> Edit Assigned Call </div>
                <div class="font-weight-bold" style="color: black"> Called By:
                    {{$call->addedBy
                                    ? ($call->addedBy->employee
                                        ? 'Employee:' . $call->addedBy->employee->name ."(".$call->addedBy->employee->employee_id .")"
                                        : ($call->addedBy->customer
                                            ? 'Customer:' . ( $call->addedBy->customer->customer_name.": ".$call->addedBy->customer->customer_code)
                                            : $call->addedBy->name))
                                    : '' }}
                </div>

            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('admin.updateCalls', $call) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="date">Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror"
                                    name="date"
                                    value="{{ old('date') ?? \Carbon\Carbon::parse($call->date_time)->format('Y-m-d') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="time">Time</label>
                                <input type="time" class="form-control @error('time') is-invalid @enderror"
                                    name="time"
                                    value="{{ old('time') ?? \Carbon\Carbon::parse($call->date_time)->format('H:i') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="team_admin">Team Head Of Other Company:</label>
                        <select id="team_admin" name="team_admin[]" multiple
                            class="form-control team_admin  tagable @error('team_admin') is-invalid @enderror ">
                            {{-- @if (!$employee->team_admin) --}}
                            @foreach ($call->refferTeamHeads as $employee_head)
                                <option selected value="{{ $employee_head->id }}">{{ $employee_head->name }}
                                    ({{ $employee_head->employee_id }})
                                    ({{ $employee_head->company->name }})
                                </option>
                            @endforeach
                            @foreach ($team_admins as $team_admin)
                                <option value="{{ $team_admin->id }}">{{ $team_admin->name }}
                                    ({{ $team_admin->employee_id }})
                                    ({{ $team_admin->company->name }})
                                </option>
                            @endforeach

                            {{-- @endif --}}
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="employee">employee:</label>
                        <select id="employee" name="employee"
                            class="form-control user-select select2-container employee-select select2"
                            data-placeholder="Employee Id / Name" data-ajax-url="{{ route('admin.employeesAllAjax') }}"
                            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200" style="">
                            @if ($call->employee)
                                <option value="{{ $call->employee->id }}">{{ $call->employee->name }}
                                    ({{ $call->employee->employee_id }})</option>
                            @endif
                        </select>
                    </div>
                    {{-- <div class="form-group col-12 col-md-6" id="userField">
                        <label for="employee">Employee:</label>
                        <select id="employee" name="employee"
                            class="form-control select2 @error('employee') is-invalid @enderror ">
                            <option value="">Select Employee</option>
                            @foreach ($myEmployees as $Memployee)
                                <option {{ $Memployee->id == $call->employee_id ? 'selected' : '' }}
                                    value="{{ $Memployee->id }}">{{ $Memployee->name }}
                                    ({{ $Memployee->employee_id }})
                                </option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer">Customer:</label>
                        <select id="customer" name="customer"
                            class="form-control user-select select2-container step2-select select2"
                            data-placeholder="Customer Code / Name" data-url="{{ route('admin.getCustomerOffice') }}"
                            data-ajax-url="{{ route('admin.customersAllAjax') }}" data-ajax-cache="true"
                            data-ajax-dataType="json" data-ajax-delay="200" style="">
                            @if ($call->customer)
                                <option value="{{ $call->customer->id }}">{{ $call->customer->customer_name }}
                                    ({{ $call->customer->customer_code }})</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6" id="location-mf">
                        <label for="customer_office">Customer Address/Locaiton: <a href="" id="add-new-location"
                                class="btn btn-sm btn-info"><i class="fas fa-plus"></i></a></label>
                        <div class="customer_address_location">
                            <select id="customer_office" name="customer_address"
                                class="form-control select2_customer_office select2-container step2-select select2"
                                data-placeholder="Select Customer Office Or Add New"
                                data-ajax-url="{{ route('global.addOrEditCustomer', ['customer' => $call->customer_id]) }}"
                                data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200" style="width: 100%;">
                                @if ($call->customer_address)
                                    <option selected value="{{ $call->customer_address }}">{{ $call->customer_address }}
                                    </option>
                                @endif

                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="type" class="form-label">Call Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">Select Call Type</option>
                            <option {{$call->type == "service"? 'selected' : ''}} value="service">Service Call</option>
                            <option {{$call->type == "warranty"? 'selected' : ''}} value="warranty">Warranty Call</option>
                            <option {{$call->type == "amc"? 'selected' : ''}} value="amc">AMC Call</option>
                            <option {{$call->type == "installation"? 'selected' : ''}} value="installation">Installation</option>
                            <option {{$call->type == "demonstration"? 'selected' : ''}} value="demonstration">Demonstration</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="service_address" class="form-label service_address_label">Service Address</label>
                        <input type="text" class="form-control" name="service_address" value="{{$call->service_address}}" placeholder="service address">
                    </div>

                    {{-- <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer_office_location">Customer Office Location:</label>
                        <select id="customer_office_location" name="customer_office_location"
                            class="form-control select2 @error('customer_office_location') is-invalid @enderror ">
                            <option value="">Select Customer Office</option>
                            @foreach ($customer_office as $office)
                                <option {{ $office->id == $call->customer_location_id ? 'selected' : '' }}
                                    value="{{ $office->id }}"> {{ $office->title }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    {{-- <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer_address">Customer Address/Locaiton:</label>
                        <input type="text" name="customer_address" class="form-control" value="{{ $call->customer_address }}">

                    </div> --}}

                    {{-- <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer_office">Customer Address/Locaiton:</label>
                        <select id="customer_office" name="customer_address"
                            class="form-control select2_customer_office"
                            data-placeholder="Select Customer Office Or Add New"
                            data-ajax-url="{{ route('global.addOrEditCustomer') }}" data-ajax-cache="true"
                            data-ajax-dataType="json" data-ajax-delay="200" style="width: 100%;">

                            @if ($call->customer_address)
                            <option selected value="{{$call->customer_address}}">{{$call->customer_address}}</option>
                            @endif
                        </select>
                    </div> --}}


                    <div class="col-12">
                        <label for="purpose_of_visit" class="form-label">Purpose of Visit</label>
                        <textarea name="purpose_of_visit" id="purpose_of_visit" cols="30" rows="2"
                            class="form-control @error('purpose_of_visit') is-invalid @enderror">{{ $call->purpose_of_visit }}</textarea>
                    </div>

                    <div class="col-12">
                        <label for="admin_note" class="form-label">Admin Note</label>
                        <textarea name="admin_note" id="admin_note" cols="30" rows="2"
                            class="form-control @error('admin_note') is-invalid @enderror">{{ $call->admin_note }}</textarea>
                    </div>

                    <div class="col-12">

                        <div class="col-12 col-md-6 text-start pt-2">
                            <label for=""></label>
                            <label for="approved_at" class="form-label"><input {{ $call->approved_at ? 'checked' : '' }} type="checkbox" name="approved_at" id="approved_at"> Approved? </label>
                        </div>
                        <div class="col-12 col-md-6 text-start pt-2">
                            <label for=""></label>
                            <label for="inhouse_product" class="form-label"><input type="checkbox"
                                    name="inhouse_product" id="inhouse_product" {{ $call->inhouse_product ? 'checked' : '' }}> Inhouse Product? </label>
                        </div>
                        <div class="text-right pt-2">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </div>



                </div>
            </form>
        </div>
    </div>
@endsection



@push('js')
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('.team_admin').select2({
                placeholder: "Select Team Head Of Other Company"
            });
            $('.step2-select').select2({
                theme: 'bootstrap4',
                // minimumInputLength: 1,
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
                            obj.text = obj.customer_name + "(" + obj.customer_code + ")";
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
            $('.employee-select').select2({
                theme: 'bootstrap4',
                // minimumInputLength: 1,
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
            console.log(finalUrl);
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    console.log(res.success);
                    if (res.success) {

                        $("#location-mf").html(res.html);
                        $('.select2_customer_office').select2({
                            // theme: 'bootstrap4',
                            // minimumInputLength: 1,
                            tags: true,
                            tokenSeparators: [','],
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
                                        obj.id = obj.id || obj.title;
                                        return obj;
                                    });
                                    var data = $.map(data, function(obj) {
                                        obj.text = obj.text || obj.title + "(" + obj
                                            .company + "-" + obj.other + ")";
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
                        $('#add-new-location').click(function(e) {
                            e.preventDefault()
                            var address =
                                `<input type="text" name="customer_address" class="customer_address_location form-control"  placeholder="Add Address">`;
                            $('.customer_address_location').html('');
                            $('.customer_address_location').html(address);


                        })
                    } else {
                        $("#location-mf").html(' ');
                    }

                }
            })
        });
    </script>

    <script>
       $('.select2_customer_office').select2({
                            // theme: 'bootstrap4',
                            // minimumInputLength: 1,
                            tags: true,
                            tokenSeparators: [','],
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
                                        obj.id = obj.id || obj.title;
                                        return obj;
                                    });
                                    var data = $.map(data, function(obj) {
                                        obj.text = obj.text || obj.title + "(" + obj
                                            .company + "-" + obj.other + ")";
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
    </script>
@endpush
