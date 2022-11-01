@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Create Visit Plan
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> Create   Visit Plans
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('employee.customerVisit.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="date">Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror"
                                    name="date" value="{{ old('date') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="time">Time</label>
                                <input type="time" class="form-control @error('time') is-invalid @enderror"
                                    name="time" value="{{ old('time') }}">
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
                                <option value="0">Select Employee</option>
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
                        <select id="customer" name="customer"
                            class="form-control customer-select select2-container step2-select select2"
                            data-placeholder="Customer Code / Name"
                            data-ajax-url="{{ route('employee.customerAllAjax') }}"
                            data-my-url="{{ route('employee.getCustomerOffice') }}"
                             data-ajax-cache="true"
                            data-ajax-dataType="json" data-ajax-delay="200" style="">

                        </select>
                    </div>
                    <div class="form-group col-12 col-md-6" id="location-mf">

                    </div>
                    <div class="form-group col-12 col-md-6" id="location-mf">
                        <label for="customer_office">Service/Delivery Address: </label>
                        <div class="service_address">
                            <input type="text" name="service_address" id="service_address" value="{{old('service_address')}}" class="form-control">
                        </div>
                    </div>


                    @if ($type != 'assign')
                        <div class="col-12 col-md-6">
                            <label for="payment_collection_date">Payment Collection Date</label>
                            <input type="date"
                                class="form-control @error('payment_collection_date') is-invalid @enderror "
                                name="payment_collection_date" value="{{ old('payment_collection_date') }}">
                        </div>
                    @endif

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
                            class="form-control @error('purpose_of_visit') is-invalid @enderror"></textarea>
                    </div>

                    <div class="col-12">
                        @if ($employee->team_admin)
                            <div class="col-12 col-md-6 text-start pt-2">
                                <label for=""></label>
                                <label for="approved" class="form-label"><input type="checkbox" checked name="approved"
                                        id="approved"> Approved? </label>
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
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script>
        $(document).on('click','.service_type',function(){
            var that = $(this);
            if(that.is(':checked')){
                if (that.val() == 'service') {
                    $('#service_address').attr('required',true);
                }else{
                    $('#service_address').attr('required',false);
                }
            }else{
                $('#service_address').attr('required',false);
            }
        })

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
    <script>
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
@endpush
