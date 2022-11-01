@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Customers Creation
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
            <div class="card-title">Customers Creation of Employee: {{$employee->name}} ({{$employee->employee_id}})
                {{-- <a href="{{ route('admin.customer.create') }}" class="btn btn-danger"><i
                        class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form method="post" action="{{ route('admin.employeeCustomersStore',$employee) }}">
                {{ csrf_field() }}

                <div class="row">
                    <div class="form-group col-12 col-md-6">
                        <label for="company_name">Customer Company</label>
                        <select name="company_name" id="company_name" class="form-control">
                            <option value="">Select Company</option>
                            @foreach ($companies as $company)
                            <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>

                        {{-- <input type="text" name="company_name" id="company_name"
                            class="form-control @error('company_name') is-invalid @enderror"> --}}
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="customer_code">Customer Code</label>
                        <input type="text" name="customer_code" id="customer_code"
                            class="form-control @error('customer_code') is-invalid @enderror">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" name="customer_name" id="customer_name"
                            class="form-control @error('customer_name') is-invalid @enderror">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="client_address">Client Address</label>
                        <input type="text" name="client_address" id="client_address"
                            class="form-control @error('client_address') is-invalid @enderror">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone"
                            class="form-control @error('phone') is-invalid @enderror">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="area">Area</label>
                        <input type="text" name="area" id="area"
                            class="form-control @error('area') is-invalid @enderror">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="division">Division</label>
                        <select name="division" id="division"
                            class="form-control custom-select div-select @error('division') is-invalid @enderror ">
                            <option value="">Select Division</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="district">District</label>
                        <select name="district" id="district"
                            class="form-control custom-select dist-select @error('district') is-invalid @enderror ">
                            <option value="">Select District</option>

                        </select>
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="thana">Thana</label>
                        <select name="thana" id="thana"
                            class="form-control custom-select thana-select @error('thana') is-invalid @enderror ">
                            <option value="">Select Thana</option>

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
                            class="form-control @error('customer_type') is-invalid @enderror">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="contact_person_name">Contact Person Name</label>
                        <input type="text" name="contact_person_name" id="contact_person_name"
                            class="form-control @error('contact_person_name') is-invalid @enderror">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="designation">Designation</label>
                        <input type="text" name="designation" id="designation"
                            class="form-control @error('designation') is-invalid @enderror">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="mobile">Mobile</label>
                        <input type="text" name="mobile" id="mobile"
                            class="form-control @error('mobile') is-invalid @enderror">
                    </div>

                    <div class="form-group col-12 col-md-6">
                        <label for="ledger_balance">Ledger Balance</label>
                        <input type="number" step="any" name="ledger_balance"
                            class="form-control @error('ledger_balance') is-invalid @enderror">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <br>
                        <label for="active"><input type="checkbox" name="active" id="active"> Active</label>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection



@push('js')
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js') }}"></script>
    <script>
        function newuser() {
            $('#userField').html(`
      <label for="user">New User Name:</label>
        <input type="text" name="name" class="form-control mb-3" placeholder="Name">
      <label for="user">New User Email:</label>
        <input type="text" name="new_user_email" class="form-control mb-3" placeholder="ex. exampl@example.com">
        `)
            $('#addUserButton').remove()
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                minimumInputLength: 1,
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
                            obj.id = obj.id || obj.username;
                            return obj;
                        });
                        var data = $.map(data, function(obj) {
                            obj.text = obj.text || obj.name + "(" + obj.employee_id + ")";
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
