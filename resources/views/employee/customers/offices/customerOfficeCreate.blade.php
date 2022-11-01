@extends('employee.layouts.employeeMaster')
@push('title')
    | employee Dashboard |Cusomer Compnay Office Location Create
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Cusomer Compnay Office Location Create
                <a href="{{route('employee.myCustomerOffices',$customer)}}" class="btn btn-warning">Back</a>
            </div>
        </div>
        <div class="card-body">
            @include('alerts.alerts')
            <form action="{{ route('employee.myCustomerOfficeStore',['customer'=>$customer]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                        <div class="form-group col-12 col-md-6">
                            <label for="title">Location Name </label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror "
                                id="title" placeholder="Location Name Here">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="location-input">Google Location</label>
                                <input type="text" name="location"
                                    class="form-control pac-target-input @error('location') is-invalid @enderror"
                                    id="location-input" placeholder="Google Location" autocomplete="off">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="lat">Latitude</label>
                            <input type="text" name="lat" class="form-control @error('lat') is-invalid @enderror " id="lat"
                                placeholder="Latitude Here">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="lng">Longitude</label>
                            <input type="text" name="lng" class="form-control @error('lng') is-invalid @enderror " id="lng"
                                placeholder="Longitude Here">
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

                        <div class="form-group col-12 col-md-6">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror " id="lng"
                                placeholder="Address">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="serial_no">Serial No</label>
                            <input type="text" name="serial_no" class="form-control @error('serial_no') is-invalid @enderror " id="lng"
                                placeholder="serial_no">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror " id="lng"
                                placeholder="start_date">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror " id="lng"
                                placeholder="end_date">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="amc_number">AMC Number</label>
                            <input type="text" name="amc_number" class="form-control @error('amc_number') is-invalid @enderror " id="lng"
                                placeholder="amc_number">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="billing_period">Billing Period</label>
                            <input type="text" name="billing_period" class="form-control @error('billing_period') is-invalid @enderror " id="lng"
                                placeholder="billing_period">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="item_code">Item Code</label>
                            <input type="text" name="item_code" class="form-control @error('item_code') is-invalid @enderror " id="lng"
                                placeholder="item_code">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="location_type">location Type</label>
                            <select name="location_type" id="location_type"
                                class="form-control custom-select @error('location_type') is-invalid @enderror ">
                                <option value="">Select location type</option>
                                <option value="booth">Booth</option>
                                <option value="">Other</option>

                            </select>
                        </div>


                        <div class="form-group col-12 col-md-6">
                            <label for="booth_id">Booth ID</label>
                            <input type="text" name="booth_id" class="form-control @error('booth_id') is-invalid @enderror " id="lng"
                                placeholder="booth_id">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="booth_name">Booth Name</label>
                            <input type="text" name="booth_name" class="form-control @error('booth_name') is-invalid @enderror " id="lng"
                                placeholder="booth_name">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="mobile_number">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror " id="lng"
                                placeholder="mobile_number">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="ups_brand">UPS Brand</label>
                            <input type="text" name="ups_brand" class="form-control @error('ups_brand') is-invalid @enderror " id="lng"
                                placeholder="ups_brand">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="model">Model</label>
                            <input type="text" name="model" class="form-control @error('model') is-invalid @enderror " id="lng"
                                placeholder="model">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="capacity">Capacity</label>
                            <input type="text" name="capacity" class="form-control @error('capacity') is-invalid @enderror " id="lng"
                                placeholder="capacity">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="battery_brand">Battery Brand</label>
                            <input type="text" name="battery_brand" class="form-control @error('battery_brand') is-invalid @enderror " id="lng"
                                placeholder="battery_brand">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="battery_ah">Battery AH</label>
                            <input type="text" name="battery_ah" class="form-control @error('battery_ah') is-invalid @enderror " id="lng"
                                placeholder="battery_ah">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="battery_qty">Battery QTY</label>
                            <input type="number" name="battery_qty" class="form-control @error('battery_qty') is-invalid @enderror " id="lng"
                                placeholder="battery_qty">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="installation_date">Installation Date</label>
                            <input type="date" name="installation_date" class="form-control @error('installation_date') is-invalid @enderror " id="lng"
                                placeholder="installation_date">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="warrenty_exipred_date">Warrenty Exipred Date</label>
                            <input type="date" name="warrenty_exipred_date" class="form-control @error('warrenty_exipred_date') is-invalid @enderror " id="lng"
                                placeholder="warrenty_exipred_date">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="amc_amount_per_month">AMC Amount Per Month</label>
                            <input type="number" name="amc_amount_per_month" class="form-control @error('amc_amount_per_month') is-invalid @enderror " id="lng"
                                placeholder="amc_amount_per_month">
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label for="active"><input type="checkbox" name="active" id="active"> Active</label>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="asset"><input type="checkbox" name="asset" id="asset"> Asset</label>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="featured_image" class="col-sm-5 form-control-label">Feature Image</label>
                            <div class="col-sm-8">
                                <input type="file" name="featured_image" class="" id="featured_image">
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="feature_image" class="col-sm-5 form-control-label"></label>
                            <div class="col-sm-8">
                                <input type="submit" class="form-control btn btn-info">
                            </div>
                        </div>

                </div>

            </form>
        </div>
    </div>
@endsection

@push('js')
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcjU4Z83IrRvF3DdDYqsBW66_2eUC8krU&libraries=places&callback=initAutocomplete"
        async defer></script>
    <script>
        var autocomplete;

        function initAutocomplete() {
            // Create the autocomplete object, restricting the search to geographical
            // location types.
            autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */
                (document.getElementById('location-input')), {
                    types: ['geocode']
                });

            // When the user selects an address from the dropdown, populate the address
            // fields in the form.
            autocomplete.addListener('place_changed', fillInAddress);
        }

        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();

            document.getElementById('lat').value = place.geometry.location.lat();
            document.getElementById('lng').value = place.geometry.location.lng();
            document.getElementById('location-selected-text').value = place.formatted_address;


        }
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
