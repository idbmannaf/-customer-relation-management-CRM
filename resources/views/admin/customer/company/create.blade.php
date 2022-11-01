@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Company Creation
@endpush

@push('css')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 m-auto">
            <div class="card shadow">
                <div class="card-header bg-info">
                    <div class="card-title">
                       Customer Company Creation
                    </div>
                </div>
                @include('alerts.alerts')
                <div class="card-body">
                    <form action="{{ route('admin.customer_company.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Company Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invald @enderror()" placeholder="Company Name here...">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Company Address</label>
                            <input type="text" name="address" id="address"
                                class="form-control @error('address') is-invald @enderror()" placeholder="Address here...">
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="active"> <input type="checkbox" name="active" id="active"> Active</label>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('js')
    <script src="{{ asset('js/location.min.js') }}"></script>
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
@endpush
