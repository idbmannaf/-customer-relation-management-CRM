@extends('admin.layouts.adminMaster')
@push('title')
    Emplyee Dashboard |Edit Visit Plan
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
            <div class="card-title"> Edit Visit Plans
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('admin.visitPlan.edit',$visitPlan) }}"
                method="POST">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="date">Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date"
                                    value="{{ old('date') ?? \Carbon\Carbon::parse($visitPlan->date_time)->format('Y-m-d') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="time">Time</label>
                                <input type="time" class="form-control @error('time') is-invalid @enderror" name="time"
                                    value="{{ old('time') ?? \Carbon\Carbon::parse($visitPlan->date_time)->format('H:i') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="employee">Employee: <i class="fas fa-info"
                                title="If you Not Select Employee Then You are the Employee of This Customer"></i>
                        </label>
                        <select id="employee" name="employee"
                            data-url="{{ route('admin.visitPlanEmployeeToCustomers') }}"
                            class="form-control select2-container step2-select select2 @error('employee') is-invalid @enderror">
                            <option value="0">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option {{ $employee->id == $visitPlan->employee_id ? 'selected' : '' }}
                                    value="{{ $employee->id }}">{{ $employee->name }}
                                    ({{ $employee->employee_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-6" id="userField">
                        <label for="customer">Customer:</label>
                        <select id="customer" name="customer"
                        data-url="{{ route('admin.visitPlanCustomersToOffice') }}"
                            class="form-control select2 @error('customer') is-invalid @enderror ">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option {{ $customer->id == $visitPlan->customer_id ? 'selected' : '' }}
                                    value="{{ $customer->id }}">{{ $customer->customer_name }}
                                    ({{ $customer->customer_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group col-12 col-md-6" id="userField">

                        <div class="form-group">
                            <label for="customer_office">Customer Address/Locaiton:</label>
                            <select id="customer_office" name="customer_address"
                                class="form-control customer_office"
                                data-placeholder="Select Customer Office Or Add New"
                                data-ajax-url="{{ route('global.addOrEditCustomer') }}" data-ajax-cache="true"
                                data-ajax-dataType="json" data-ajax-delay="200" style="width: 100%;">
                                @if ($visitPlan->customer_address)
                                <option selected value="{{$visitPlan->customer_address}}">{{$visitPlan->customer_address}}</option>
                                @endif

                            </select>
                        </div>
                        {{-- <label for="customer_address">Customer Address/Location:</label>
                        <input type="text" name="customer_address" value="{{ old('customer_address') }}"
                            class="form-control"> --}}
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="payment_collection_date">Payment Collection Date</label>
                        <input type="date" class="form-control @error('payment_collection_date') is-invalid @enderror "
                            name="payment_collection_date" value="{{ old('payment_collection_date') ?? $visitPlan->payment_collection_date }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="payment_maturity_date">Payment Maturity Date</label>
                        <input type="date" class="form-control @error('payment_maturity_date') is-invalid @enderror "
                            name="payment_maturity_date" value="{{ old('payment_maturity_date') ?? $visitPlan->payment_maturity_date }}">
                    </div>

                    <div class="col-12">
                        <label for="purpose_of_visit" class="form-label">Purpose of Visit</label>
                        <textarea name="purpose_of_visit" id="purpose_of_visit" cols="30" rows="2"
                            class="form-control @error('purpose_of_visit') is-invalid @enderror">
                            {{$visitPlan->purpose_of_visit}}
                        </textarea>
                    </div>

                    <div class="col-12">
                        <div class="col-12 col-md-6 text-start pt-2">
                            <label for=""></label>
                            <label for="approved" class="form-label"><input {{$visitPlan->team_admin_approved_at ? 'checked' : ''}} type="checkbox" name="approved"
                                    id="approved"> Approved? </label>
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
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js') }}"></script>


    <script>
        $('.select2').select2();
        $("#employee").change(function() {
            var that = $(this);
            var url = that.attr('data-url');
            var finalUrl = url + "?employee=" + that.val();
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    $("#customer_office_location").html('');
                    if (res.success) {
                        $("#customer").html(res.html);
                    } else {
                        $("#customer").html(`<option value="">No Customer Found
                                </option>`);
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
            $('.customer_office').select2({
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
