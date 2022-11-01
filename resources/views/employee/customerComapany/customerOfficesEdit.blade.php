@extends('employee.layouts.employeeMaster')
@push('title')
    | employee Dashboard | Office Location Create
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Office Locations Create
                <a href="{{route('admin.customerCompanyOffice',$customer_company)}}" class="btn btn-danger">back</a>
            </div>
        </div>

        <div class="card-body">
            @include('alerts.alerts')
            <form action="{{ route('employee.customerCompanyOfficeEdit',['office'=>$office,'customer_company'=>$customer_company]) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="title">Location Name</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror "
                                id="title" placeholder="Location Name Here" value="{{ $office->title }}">
                        </div>


                        @if ($office->active == false || $employee->team_admin)
                        <div class="form-group">
                            <div class="col-md-12 my-2">
                                <label for="location-input">Google Location</label>
                                <input type="text" name="location"
                                    class="form-control pac-target-input @error('location') is-invalid @enderror"
                                    id="location-input" placeholder="Google Location" autocomplete="off"
                                    value="{{ $office->google_location }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lat">Latitude</label>
                            <input type="text" name="lat" class="form-control @error('lat') is-invalid @enderror " id="lat"
                                placeholder="Latitude Here" value="{{ $office->lat }}">
                        </div>

                        <div class="form-group">
                            <label for="lng">Longitude</label>
                            <input type="text" name="lng" class="form-control @error('lng') is-invalid @enderror " id="lng"
                                placeholder="Longitude Here" value="{{ $office->lng }}">
                        </div>
                        @endif
                        {{-- <div class="form-group">
                            <label for="office_start_time">Office Start Time</label>
                            <input type="time" name="office_start_time" class="form-control  @error('office_start_time') is-invalid @enderror " id="lng" placeholder="Start Time  Here"  value="{{$office->office_start_time}}">
                        </div>
                        <div class="form-group">
                            <label for="office_end_time">Office End Time</label>
                            <input type="time" name="office_end_time" class="form-control  @error('office_end_time') is-invalid @enderror  " id="lng" placeholder="end Time  Here" value="{{$office->office_end_time}}">
                        </div> --}}

                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="division">Division</label>
                            <select name="division" id="division"
                                class="form-control custom-select div-select @error('division') is-invalid @enderror ">
                                <option value="">Select Division</option>
                                @foreach ($divisions as $division)
                                    <option {{ $office->division_id == $division->id ? 'selected' : '' }}
                                        value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="district">District</label>
                            <select name="district" id="district"
                                class="form-control custom-select dist-select @error('district') is-invalid @enderror ">
                                <option value="">Select District</option>
                                @foreach ($selected_districts as $district)
                                <option {{$office->district_id == $district->id ? 'selected': ''}} value="{{ $district->id }}">{{ $district->name }}
                                @endforeach


                            </select>
                        </div>

                        <div class="form-group">
                            <label for="thana">Thana</label>
                            <select name="thana" id="thana"
                                class="form-control custom-select thana-select @error('thana') is-invalid @enderror ">
                                <option value="">Select Thana</option>
                                @foreach ($selected_thanas as $thana)
                                <option {{$office->thana_id == $thana->id ? 'selected': ''}} value="{{ $thana->id }}">{{ $thana->name }}
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="form-group">
                            <label for="company">Company</label>
                            <select name="company" id="company"
                                class="form-control custom-select @error('company') is-invalid @enderror ">
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option {{ $company->id == $office->company_id ? 'selected' :'' }} value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="form-group ">
                            <label for="featured_image" class="col-sm-5 form-control-label">Feature Image</label>
                            <div class="col-sm-8">
                                <input type="file" name="featured_image" class="" id="featured_image">
                            </div>
                            @if ($office->featured_image)
                                <img
                                    src="{{ route('imagecache', ['template' => 'sbixs', 'filename' => $office->fi()]) }}" />
                                <br>
                            @endif
                        </div>
                        @if ($employee->team_admin)
                        <div class="form-group ">
                            <label for="active"><input type="checkbox" {{$office->active ? 'checked' : ''}} name="active" id="active"> Active</label>
                        </div>
                        @endif
                        <div class="form-group ">
                            <label for="feature_image" class="col-sm-5 form-control-label"></label>
                            <div class="col-sm-8">
                                <input type="submit" value="Update" class="form-control btn btn-info">
                            </div>
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
        $(document).ready(function () {
            var dists = <?php echo json_encode($districts); ?>;
            var thanas = <?php echo json_encode($thanas); ?>

            $(document).on("change", ".div-select", function (e) {
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

                $.each(dists, function (i, item) {
                    if (item.division_id == q) {
                        that.closest('form').find(".dist-select").append(
                            "<option value='" + item.id + "'>" + item.name +
                            "</option>");
                    }
                });

                $.each(thanas, function (i, item) {
                    if (item.division_id == q) {
                        that.closest('form').find(".thana-select").append(
                            "<option value='" + item.id + "'>" + item.name +
                            "</option>");
                    }
                });

            });


            $(document).on("change", ".dist-select", function (e) {
                // e.preventDefault();

                var that = $(this);
                var q = that.val();

                that.closest('form').find(".thana-select").empty().append($('<option>', {
                    value: '',
                    text: 'Thana'
                }));

                $.each(thanas, function (i, item) {
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

