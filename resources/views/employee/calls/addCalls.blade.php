@extends('employee.layouts.employeeMaster')
@push('title')
    Employee Dashboard | Attendance Report
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
            <div class="card-title"> Assign New Service Call
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('employee.addCalls') }}" method="POST">
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
                    {{-- @if ($employee->team_admin) --}}
                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="team_admin">Team Head Of Other Company:</label>
                        <select id="team_admin" name="team_admin[]" multiple
                            class="form-control select3  tagable @error('team_admin') is-invalid @enderror ">
                            {{-- @if (!$employee->team_admin) --}}
                            @foreach ($team_admins as $team_admin)
                                <option value="{{ $team_admin->id }}">{{ $team_admin->name }}
                                    ({{ $team_admin->employee_id }})
                                    ({{ $team_admin->company->name }})
                                </option>
                            @endforeach

                            {{-- @endif --}}
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="type" class="form-label">Call Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">Select Call Type</option>
                            <option value="service">Service Call</option>
                            <option value="warranty">Warranty Call</option>
                            <option value="amc">AMC Call</option>
                            <option value="installation">Installation</option>
                            <option value="demonstration">Demonstration</option>
                        </select>
                    </div>

                    {{-- @endif --}}
                    @if ($employee->team_admin)
                        <div class="form-group col-12 col-md-6" id="userField">
                            <label for="employee">Employee:</label>
                            <select id="employee" name="employee"
                                class="form-control select2 @error('employee') is-invalid @enderror ">
                                <option value="">Select Employee</option>
                                {{-- @if (!$employee->team_admin) --}}
                                @foreach ($myEmployees as $Memployee)
                                    <option value="{{ $Memployee->id }}">{{ $Memployee->name }}
                                        ({{ $Memployee->employee_id }})
                                    </option>
                                @endforeach
                                {{-- @endif --}}
                            </select>
                        </div>
                    @endif
                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer">Customer:</label>
                        <select id="customer" name="customer"
                            class="form-control customer-select select2-container step2-select select2"
                            data-placeholder="Customer Code / Name" data-ajax-url="{{ route('employee.customerAllAjax') }}"
                            data-my-url="{{ route('employee.getCustomerOffice') }}" data-ajax-cache="true"
                            data-ajax-dataType="json" data-ajax-delay="200" style="">

                        </select>
                    </div>

                    <div class="form-group col-12 col-md-6" id="location-mf">

                    </div>

                    <div class="col-12 col-md-6">
                        <label for="service_address" class="form-label service_address_label">Service Address</label>
                        <input type="text" class="form-control" name="service_address" placeholder="service address">
                    </div>

                    <div class="col-12">
                        <label for="purpose_of_visit" class="form-label">Purpose of Visit</label>
                        <textarea name="purpose_of_visit" id="purpose_of_visit" cols="30" rows="2"
                            class="form-control @error('purpose_of_visit') is-invalid @enderror"></textarea>
                    </div>

                    @if ($employee->team_admin)
                        <div class="col-12">
                            <label for="admin_note" class="form-label">Admin Note</label>
                            <textarea name="admin_note" id="admin_note" cols="30" rows="2"
                                class="form-control @error('admin_note') is-invalid @enderror"></textarea>
                        </div>
                    @endif

                    <div class="col-12 ">
                        @if ($employee->team_admin)
                            <div class="col-12 col-md-6 text-start pt-2">
                                <label for=""></label>
                                <label for="approved_at" class="form-label"><input type="checkbox" name="approved_at"
                                        id="approved_at" checked> Approved? </label>
                            </div>
                            <div class="col-12 col-md-6 text-start pt-2">
                                <label for=""></label>
                                <label for="inhouse_product" class="form-label"><input type="checkbox"
                                        name="inhouse_product" id="inhouse_product"> Inhouse Product? </label>
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
        $('.select2').select2();
        $('.select3').select2({
            placeholder: "Select Team Head Of Other Company"
        });
        $("#employee").change(function() {
            var that = $(this);

            if (Number(that.val()) > 0) {
                $('.showBaseInEmployee').fadeIn();
                // $('#approved_at').attr('required',true);
            } else {
                $('.showBaseInEmployee').fadeOut();
                // $('#approved_at').attr('required',false);
            }

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
    <script>
        // $('.select2_customer_office').select2({
        //     // theme: 'bootstrap4',
        //     // minimumInputLength: 1,
        //     tags: true,
        //     tokenSeparators: [','],
        //     ajax: {
        //         data: function(params) {
        //             return {
        //                 q: params.term, // search term
        //                 page: params.page
        //             };
        //         },
        //         processResults: function(data, params) {
        //             params.page = params.page || 1;
        //             // alert(data[0].s);
        //             var data = $.map(data, function(obj) {
        //                 obj.id = obj.id || obj.title;
        //                 return obj;
        //             });
        //             var data = $.map(data, function(obj) {
        //                 obj.text = obj.text || obj.title +"("+obj.company+"-"+obj.other+")";
        //                 return obj;
        //             });
        //             return {
        //                 results: data,
        //                 pagination: {
        //                     more: (params.page * 30) < data.total_count
        //                 }
        //             };
        //         }
        //     },
        // });
        $('#type').change(function() {
            $('.service_address_label').text($(this).val().toLowerCase() + " Address");
        })
    </script>
@endpush
