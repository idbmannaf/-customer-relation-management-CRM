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
                        Company Creation
                    </div>
                </div>
                @include('alerts.alerts')
                <div class="card-body">
                    <form action="{{ route('admin.company.store') }}" method="POST">
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
                            <label for="team_head_access_all_customers"> <input type="checkbox"name="team_head_access_all_customers" id="team_head_access_all_customers">
                                Team Head Access All Customers</label>
                        </div>
                        <div class="form-group">
                            <label for="team_member_access_all_customers"> <input type="checkbox" name="team_member_access_all_customers" id="team_member_access_all_customers">
                                Team Member Access All customers</label>
                        </div>
                        <div class="form-group">
                            <label for="logo_and_req_permission"> <input type="checkbox" name="logo_and_req_permission" id="logo_and_req_permission">
                                log and req permission (Service TH)</label>
                        </div>
                        <div class="form-group">
                            <label for="inventory_maintain_permission"> <input type="checkbox" name="inventory_maintain_permission" id="inventory_maintain_permission">
                                Inventory maintain permission (Store TH)</label>
                        </div>
                        <div class="form-group">
                            <label for="account_maintain_permission"> <input type="checkbox" name="account_maintain_permission" id="account_maintain_permission">
                                Accounts maintain permission (ACCOUNTS TH)</label>
                        </div>
                        <div class="form-group">
                            <label for="access_all_call"> <input type="checkbox" name="access_all_call" id="access_all_call">
                                Access All Calls (Service TH)</label>
                        </div>
                        <div class="form-group">
                            <label for="access_all_call_visit_plan_without_call"> <input type="checkbox" name="access_all_call_visit_plan_without_call" id="access_all_call_visit_plan_without_call">
                                Access All Visit Plan (Without Call) (Merketing TH)</label>
                        </div>
                        <div class="form-group">
                            <label for="store_damage_product_assign_permission">  <input type="checkbox"  name="store_damage_product_assign_permission" id="store_damage_product_assign_permission">
                               Store Damage Product Assign Permission (Service TH)</label>
                        </div>
                        <div class="form-group">
                            <label for="active"> <input type="checkbox" name="active" id="active"> Active</label>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </form>
                    {{-- <h3 class="shadow">Bulk Upload Here</h3>
                    <div class="card-body">
                        <form action="{{route('admin.company.store',['type'=>'bulk'])}}" method="post" enctype="multipart/form-data" >
                            @csrf
                            <input type="file" name="file">
                            <input type="submit" class="btn btn-info" value="Bulk Upload">
                        </form>
                    </div> --}}
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
