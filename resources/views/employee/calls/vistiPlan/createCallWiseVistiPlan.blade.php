@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Create Visit Plan
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
            <div class="card-title"> Create Assign To (Visit Plan)
                {{-- @if (request()->inhouse)
                        <a href="{{ route('employee.addVisitPlan', ['call'=>$call,'inhouse'=>true]) }}" class="btn btn-danger btn-sm">New Visit Plan</a>
                    @else
                        <a href="{{ route('employee.addVisitPlan', $call) }}" class="btn btn-danger btn-sm">New Visit
                            Plan</a>
                    @endif --}}
                <a href="{{route('employee.callWiseVisitPlan',$call)}}" class="btn btn-danger btn-sm">Back</a>
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
                            <b>Customer Address/Location: </b> {{ $call->customer_office ? $call->customer_office->title : $call->customer_address }} <br>
                            <b>{{ucfirst($call->type)}} Address: </b> {{ $call->service_address }} <br>
                            {{-- <b>Customer Employee: </b> {{ $call->customer->employee->name }} <br> --}}
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
                                <b>Employee: </b> {{ $call->employee->name }} <br>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('employee.addVisitPlan', $call) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="date">Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date"
                                    value="{{ old('date') ?? \Carbon\Carbon::parse($call->date_time)->format('Y-m-d') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="time">Time</label>
                                <input type="time" class="form-control @error('time') is-invalid @enderror" name="time"
                                    value="{{ old('time') ?? \Carbon\Carbon::parse($call->date_time)->format('H:i') }}">
                            </div>
                        </div>
                    </div>
                    @if ($employee->team_admin)
                        <div class="form-group col-12 col-md-6" id="userField">
                            <label for="employee">Employee: <i class="fas fa-info"
                                    title="If you Not Select Employee Then You are the Employee of This Customer"></i>
                            </label>
                            <select id="employee" name="employee"
                                data-url="{{ route('employee.employeeCustomerCheckAjas') }}"
                                class="form-control select2-container step2-select select2 @error('employee') is-invalid @enderror">
                                <option value="">Select Employee</option>
                                @foreach ($my_employees as $my_employee)
                                    <option value="{{ $my_employee->id }}">{{ $my_employee->name }}
                                        ({{ $my_employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif


                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer">Customer:</label>
                        <select id="customer" disabled name="customer" data-url="{{ route('employee.getCustomerOffice') }}"
                            class="form-control select2 @error('customer') is-invalid @enderror ">
                            <option selected value="{{ $customer->id }}">{{ $customer->customer_name }}
                                ({{ $customer->customer_code }})
                            </option>
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
                                    <option selected value="{{ $call->customer_address }}">{{ $call->customer_address }}
                                    </option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6" id="location-mf">
                        <label for="customer_office">Service/Delivery Address: </label>
                        <div class="service_address">
                            <input type="text" name="service_address"  id="service_address" value="{{$call->service_address ?? ''}}" class="form-control">
                        </div>
                    </div>
                    {{-- <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer_office">Customer Address/Locaiton:</label>
                        <select id="customer_office" name="customer_address"
                            class="form-control select2_customer_office select2-container step2-select select2"
                            data-placeholder="Select Customer Office Or Add New"
                            data-ajax-url="{{ route('global.addOrEditCustomer') }}" data-ajax-cache="true"
                            data-ajax-dataType="json" data-ajax-delay="200" style="width: 100%;">
                            <option selected value="{{$call->customer_address}}">{{$call->customer_address}}</option>

                        </select>

                    </div> --}}



                    {{-- <div class="col-12 col-md-6">
                        <label for="payment_collection_date">Payment Collection Date</label>
                        <input type="date" class="form-control @error('payment_collection_date') is-invalid @enderror "
                            name="payment_collection_date" value="{{ old('payment_collection_date') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="payment_maturity_date">Payment Maturity Date</label>
                        <input type="date" class="form-control @error('payment_maturity_date') is-invalid @enderror "
                            name="payment_maturity_date" value="{{ old('payment_maturity_date') }}">
                    </div> --}}

                    <div class="col-12 col-md-6 py-1">
                        <label for="visit_type">Service Type</label>
                        <div class="d-flex justify-content-between text-wrap">

                            <label class="d-flex align-items-center" for="service"><input type="radio" name="service_type" class="service_type" value="service" id="service">Service</label>
                            <label class="d-flex align-items-center" for="sales"><input type="radio" name="service_type" class="service_type" value="sales" id="sales">sales</label>
                            <label class="d-flex align-items-center" for="collection"><input type="radio" name="service_type" class="service_type" value="collection" id="collection">Collection</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="purpose_of_visit" class="form-label">Purpose of Visit</label>
                        <textarea name="purpose_of_visit" id="purpose_of_visit" cols="30" rows="2"
                            class="form-control @error('purpose_of_visit') is-invalid @enderror">{{$call->purpose_of_visit}}</textarea>
                    </div>

                    <div class="col-12">
                        @if ($employee->team_admin)
                            <div class="col-12 col-md-6 text-start pt-2">
                                <label for=""></label>
                                <label for="approved" class="form-label"><input type="checkbox" name="approved"
                                        id="approved" checked> Approved? </label>
                            </div>
                        @endif
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
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js') }}"></script>

    <script>
         $(document).on('click','.service_type',function(){
            var that = $(this);
            if(that.is(':checked')){
                if (that.val() == 'service') {
                    // alert('service')
                    $('#service_address').attr('required',true);
                }else{
                    $('#service_address').attr('required',false);
                }
            }else{
                $('#service_address').attr('required',false);
            }
        })
        // $('.select2').select2();
        // $("#employee").change(function() {
        //     var that = $(this);
        //     var url = that.attr('data-url');
        //     var finalUrl = url + "?employee=" + that.val();
        //     $.ajax({
        //         url: finalUrl,
        //         method: "GET",
        //         success: function(res) {
        //             console.log(res.success);
        //             if (res.success) {
        //                 $("#customer").html('');
        //                 $.each(res.customer, function(i, item) {
        //                     $("#customer").append(
        //                         "<option value='" + item.id + "'>" + item.customer_name +
        //                         "</option>");
        //                 });
        //                 $("#customer").trigger('change');
        //             } else {
        //                 $("#customer").html(' ');
        //             }

        //         }
        //     })
        // });
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
                        obj.text = obj.text || obj.title + "(" + obj.company + "-" + obj.other + ")";
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
    </script>
@endpush
