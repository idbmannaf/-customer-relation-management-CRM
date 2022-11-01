@extends('employee.layouts.employeeMaster')
@push('title')
Edit Other Customers
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> Edit Other Customers : {{$customer->customer_name}}  Edit</div>

            </div>
        </div>
        @include('alerts.alerts')
    </div>
    <div class="card-body">

        <form method="post" action="{{ route('employee.myCustomers.update',$customer) }}">
            {{ csrf_field() }}
            @method('PATCH')
            <div class="row">
                @if ($employee->team_admin)
                <div class="form-group col-12 col-md-6" id="userField">
                    <label for="employee">Employee: <i class="fas fa-info" title="If you Not Select Employee Then You are the Employee of This Customer"></i> </label>
                    <select id="employee" name="employee"
                        class="form-control select2-container step2-select select2 @error('employee') is-invalid @enderror" data-placeholder=" Employee">
                        <option value="">Select Employee</option>
                        @foreach ($my_employees as $my_employee)
                        <option {{$my_employee->id == $customer->employee_id ? 'selected' : ''}} value="{{$my_employee->id}}">{{$my_employee->name}} ({{$my_employee->id}})</option>
                        @endforeach
                    </select>
                    @error('employee')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @endif


                <div class="form-group col-12 col-md-6">
                    <label for="customer_name">Customer Name / Company Name</label>
                    <input type="text" name="customer_name" id="customer_name"
                        class="form-control @error('customer_name') is-invalid @enderror" value="{{old('customer_name') ?? $customer->company ? $customer->company->name : ''}}">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="customer_code">Customer Code</label>
                    <input type="text" name="customer_code" id="customer_code"
                        class="form-control @error('customer_code') is-invalid @enderror" value="{{old('customer_code') ?? $customer->customer_code}}">
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="client_address">Client Address</label>
                    <input type="text" name="client_address" id="client_address"
                        class="form-control @error('client_address') is-invalid @enderror" value="{{old('client_address') ?? $customer->client_address}}" >
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone"
                        class="form-control @error('phone') is-invalid @enderror" value="{{old('phone') ?? $customer->phone}}">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="area">Area</label>
                    <input type="text" name="area" id="area"
                        class="form-control @error('area') is-invalid @enderror" value="{{old('area') ?? $customer->area}}">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="division">Division</label>
                    <select name="division" id="division"
                        class="form-control custom-select div-select @error('division') is-invalid @enderror ">
                        <option value="">Select Division</option>
                        @foreach ($divisions as $division)
                            <option {{ $division->id == $customer->division ? 'selected' : '' }} value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="district">District</label>
                    <select name="district" id="district"
                        class="form-control custom-select dist-select @error('district') is-invalid @enderror ">
                        <option value="">Select District</option>
                        @foreach ($selectedDistricts as $sd)
                        <option {{ $sd->name == $customer->district ? 'selected' : '' }}
                            value="{{ $sd->id }}">{{ $sd->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="thana">Thana</label>
                    <select name="thana" id="thana"
                        class="form-control custom-select thana-select @error('thana') is-invalid @enderror ">
                        <option value="">Select Thana</option>
                        @foreach ($selectedThanas as $st)
                        <option {{ $st->name == $customer->district ? 'selected' : '' }}
                            value="{{ $st->id }}">{{ $st->name }}</option>
                    @endforeach
                    </select>
                </div>
                {{-- <div class="form-group col-12 col-md-6">
                    <label for="area">Area</label>
                    <select name="area" id="area" class="form-control">
                        <option value="">Select Area</option>
                    </select>
                    <input type="hidden" name="area_id" id="area_id" value="1">
                </div> --}}
                <div class="form-group col-12 col-md-6">
                    <label for="customer_type">Customer Type</label>
                    <input type="text" name="customer_type" id="customer_type"
                        class="form-control @error('customer_type') is-invalid @enderror" value="{{old('customer_type') ?? $customer->customer_type}}">
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="contact_person_name">Contact Person Name</label>
                    <input type="text" name="contact_person_name" id="contact_person_name"
                        class="form-control @error('contact_person_name') is-invalid @enderror" value="{{old('contact_person_name') ?? $customer->contact_person_name }}">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="designation">Designation</label>
                    <input type="text" name="designation" id="designation"
                        class="form-control @error('designation') is-invalid @enderror" value="{{old('designation') ?? $customer->designation}}">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" value="{{old('email') ?? $customer->email}}">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="mobile">Mobile</label>
                    <input type="text" name="mobile" id="mobile"
                        class="form-control @error('mobile') is-invalid @enderror" value="{{old('mobile') ?? $customer->mobile}}">
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="ledger_balance">Ledger Balance</label>
                    <input type="number" step="any" name="ledger_balance"
                        class="form-control @error('ledger_balance') is-invalid @enderror" value="{{old('ledger_balance') ?? $customer->ledger_balance}}">
                </div>
                <div class="form-group col-12 col-md-6">
                    <br>
                    <label for="active"><input {{ $customer->active ? 'checked' : '' }} type="checkbox" name="active" id="active"> Active</label>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

        </form>
    </div>
    </div>
@endsection


@push('js')


    <script type="text/javascript">
        $(document).ready(function() {
            var dists = <?php echo json_encode($districts); ?>;
            var thanas = <?php echo json_encode($thanas); ?>

            $(document).on("change", ".div-select", function(e) {
                // e.preventDefault();

                var that = $(this);
                var q = that.val();

                that.closest('form').find(".thana-select").empty().append($('<option>', {
                    value: '',
                    text: 'Thana'
                }));

                that.closest('form').find(".dist-select").empty().append($('<option>', {
                    value: '',
                    text: 'District'
                }));

                $.each(dists, function(i, item) {
                    if (item.division_id == q) {
                        that.closest('form').find(".dist-select").append(
                            "<option value='" + item.id + "'>" + item.name +
                            "</option>");
                    }
                });

                $.each(thanas, function(i, item) {
                    if (item.division_id == q) {
                        that.closest('form').find(".thana-select").append(
                            "<option value='" + item.id + "'>" + item.name +
                            "</option>");
                    }
                });

            });


            $(document).on("change", ".dist-select", function(e) {
                // e.preventDefault();

                var that = $(this);
                var q = that.val();

                that.closest('form').find(".thana-select").empty().append($('<option>', {
                    value: '',
                    text: 'Thana'
                }));

                $.each(thanas, function(i, item) {
                    if (item.district_id == q) {
                        that.closest('form').find(".thana-select").append(
                            "<option value='" + item.id + "'>" + item.name +
                            "</option>");
                    }
                });

            });


        });
    </script>
@endpush
