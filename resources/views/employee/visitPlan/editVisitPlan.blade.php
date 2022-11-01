@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Edit {{ $type }} Visit Plan
@endpush

@push('css')
    <link href="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css') }}"
        rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ asset('https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> Edit {{ $type }} Visit Plans
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('employee.customerVisit.update', ['type' => $type, 'visit' => $visit->id]) }}"
                method="POST">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="date">Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date"
                                    value="{{ old('date') ?? \Carbon\Carbon::parse($visit->date_time)->format('Y-m-d') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="time">Time</label>
                                <input type="time" class="form-control @error('time') is-invalid @enderror" name="time"
                                    value="{{ old('time') ?? \Carbon\Carbon::parse($visit->date_time)->format('H:i') }}">
                            </div>
                        </div>
                    </div>

                    @if ($employee->team_admin)

                        <div class="form-group col-12 col-md-6" id="userField">
                            <label for="employee">Employee: <i class="fas fa-info"
                                    title="If you Not Select Employee Then You are the Employee of This Customer"></i>
                            </label>
                            @if ($visit->call_id)
                                <select id="employee_selece" name="employee"
                                    class="form-control select2-container step2-select select2 @error('employee') is-invalid @enderror">
                                    <option value="0">Select Employee</option>
                                    @foreach ($my_employees as $my_employee)
                                        <option {{ $my_employee->id == $visit->employee_id ? 'selected' : '' }}
                                            value="{{ $my_employee->id }}">{{ $my_employee->name }}
                                            ({{ $my_employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @else
                                <select id="employee" name="employee"
                                    data-url="{{ route('employee.employeeCustomerCheckAjas') }}"
                                    class="form-control select2-container step2-select select2 @error('employee') is-invalid @enderror">
                                    <option value="0">Select Employee</option>
                                    @foreach ($my_employees as $my_employee)
                                        <option {{ $my_employee->id == $visit->employee_id ? 'selected' : '' }}
                                            value="{{ $my_employee->id }}">{{ $my_employee->name }}
                                            ({{ $my_employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @endif

                        </div>

                    @endif

                    @if ($visit->call_id)
                        <input type="hidden" name="customer" value="{{ $visit->customer->id }}">
                        <div class="form-group col-12 col-md-6" id="userField">
                            <label for="customer">Customer:</label>
                            <select id="customer" name="customer" disabled
                                class="form-control select2 @error('customer') is-invalid @enderror ">
                                <option selected value="{{ $visit->customer->id }}">
                                    {{ $visit->customer->customer_name }}
                                    ({{ $visit->customer->customer_code }})
                                </option>
                                {{-- @endif --}}
                            </select>
                        </div>
                    @else
                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer">Customer:</label>
                        <select id="customer" name="customer"
                            class="form-control customer-select select2-container step2-select select2"
                            data-placeholder="Customer Code / Name"
                            data-ajax-url="{{ route('employee.customerAllAjax') }}"
                            data-my-url="{{ route('employee.getCustomerOffice') }}"
                            data-ajax-cache="true"
                            data-ajax-dataType="json" data-ajax-delay="200" style="">
                            @if ($visit->customer)
                            <option selected value="{{$visit->customer_id}}">{{$visit->customer->customer_name}} {{$visit->customer->customer_code}}</option>
                            @endif
                        </select>
                    </div>
                    @endif

                    <div class="form-group col-12 col-md-6" id="location-mf">
                        <label for="customer_office">Customer Address/Locaiton: <a href="" id="add-new-location" class="btn btn-sm btn-info"><i class="fas fa-plus"></i></a></label>
                            <div class="customer_address_location">
                                <select id="customer_office" name="customer_address"
                                class="form-control select2_customer_office select2-container step2-select select2"
                                data-placeholder="Select Customer Office Or Add New"
                                data-ajax-url="{{ route('global.addOrEditCustomer',['customer'=>$visit->customer_id]) }}" data-ajax-cache="true"
                                data-ajax-dataType="json" data-ajax-delay="200" style="width: 100%;">
                                @if ($visit->customer_address)
                                <option selected value="{{$visit->customer_address}}">{{$visit->customer_address}}</option>
                                @endif
                            </select>
                            </div>
                    </div>
                    <div class="form-group col-12 col-md-6" id="location-mf">
                        <label for="customer_office">Service/Delivery Address: </label>
                        <div class="service_address">
                            <input type="text" name="service_address" value="{{$visit->service_address}}" class="form-control">
                        </div>
                    </div>

                    {{-- @if ($visit->call_id)
                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer_office_location">Customer Office Location:</label>
                        <select id="customer_office_location" name="customer_office_location"
                            class="form-control select2 @error('customer_office_location') is-invalid @enderror ">
                            <option value="">Select Customer Office</option>
                            @foreach ($visit->customer->company->offices as $office)
                                <option {{ $office->id == $visit->customer_office_location_id ? 'selected' : '' }}
                                    value="{{ $office->id }}">{{ $office->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer_office_location">Customer Office Location:</label>
                        <select id="customer_office_location" name="customer_office_location"
                            class="form-control select2 @error('customer_office_location') is-invalid @enderror ">
                            <option value="">Select Customer Office</option>
                            @foreach ($customerOfficeLocations as $office)
                                <option {{ $office->id == $visit->customer_office_location_id ? 'selected' : '' }}
                                    value="{{ $office->id }}">{{ $office->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer_address">Customer Address/Location:</label>
                        <input type="text" name="customer_address" value="{{$visit->customer_address}}" class="form-control">
                    </div>
                    @endif --}}


                    <div class="col-12 col-md-6">
                        <label for="payment_collection_date">Payment Collection Date</label>
                        <input type="date" class="form-control @error('payment_collection_date') is-invalid @enderror "
                            name="payment_collection_date"
                            value="{{ old('payment_collection_date') ?? $visit->payment_collection_date }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="payment_maturity_date">Payment Maturity Date</label>
                        <input type="date" class="form-control @error('payment_maturity_date') is-invalid @enderror "
                            name="payment_maturity_date"
                            value="{{ old('payment_maturity_date') ?? $visit->payment_maturity_date }}">
                    </div>
                    <div class="col-12 col-md-6 py-1">
                        <label for="visit_type">Service Type</label>
                        <div class="d-flex justify-content-between text-wrap">
                            <label class="d-flex align-items-center" for="service"><input type="radio" name="service_type" {{$visit->service_type == 'service' ? 'checked' : ''}} value="service" id="service">Service</label>
                            <label class="d-flex align-items-center" for="sales"><input type="radio" name="service_type" {{$visit->service_type == 'sales' ? 'checked' : ''}} value="sales" id="sales">sales</label>
                            <label class="d-flex align-items-center" for="collection"><input type="radio" name="service_type" {{$visit->service_type == 'collection' ? 'checked' : ''}} value="collection" id="collection">Collection</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="purpose_of_visit" class="form-label">Purpose of Visit</label>
                        <textarea name="purpose_of_visit" id="purpose_of_visit" cols="30" rows="2"
                            class="form-control @error('purpose_of_visit') is-invalid @enderror">{{ $visit->purpose_of_visit }}</textarea>
                    </div>

                    <div class="col-12">
                        @if ($employee->team_admin)
                            <div class="col-12 col-md-6 text-start pt-2">
                                <label for=""></label>
                                <label for="approved" class="form-label"><input checked type="checkbox"
                                        name="approved" id="approved"> Approved? </label>
                            </div>
                        @endif
                        <div class="text-right pt-2">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </div>



                </div>
            </form>
        </div>

        {{-- @if ($visit_plan->call)
        @include('global.callModal')
    @endif --}}
    </div>
@endsection


@push('js')
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js') }}"></script>

    <script>
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
            var url = that.attr('data-my-url');
            var finalUrl = url + "?customer=" + that.val();

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
        $('#add-new-location').click(function(e) {
                            e.preventDefault()
                            var address =
                                `<input type="text" name="customer_address" class="customer_address_location form-control"  placeholder="Add Address">`;
                            $('.customer_address_location').html('');
                            $('.customer_address_location').html(address);


                        })
    </script>
        <script>
            $('.select2_customer_office').select2({
                theme: 'bootstrap4',
                // minimumInputLength: 1,
                tags: true,
                // tokenSeparators: [','],
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
                            obj.text = obj.text || obj.title +"("+obj.company+"-"+obj.other+")";
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

<script>
    $('.customer-select').select2({
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
</script>
@endpush
