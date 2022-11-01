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
                    <form action="{{route('admin.company.update',['company'=>$company])}}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="form-group">
                            <label for="name">Copmpany Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invald @enderror()"
                                placeholder="Company Name here..." value="{{$company->name}}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Company Address</label>
                            <input type="text" name="address" id="address"
                                class="form-control @error('address') is-invald @enderror()"
                                placeholder="Address here..." value="{{$company->address}}">
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="team_head_access_all_customers"> <input type="checkbox" {{$company->team_head_access_all_customers ? 'checked' : ''}} name="team_head_access_all_customers" id="team_head_access_all_customers">
                                Team Head Access All Customers</label>
                        </div>
                        <div class="form-group">
                            <label for="team_member_access_all_customers"> <input type="checkbox" {{$company->team_member_access_all_customers ? 'checked' : ''}} name="team_member_access_all_customers" id="team_member_access_all_customers">
                                Team Member Access All customers</label>
                        </div>

                        <div class="form-group">
                            <label for="logo_and_req_permission">
                                <input type="checkbox"  {{$company->logo_and_req_permission ==1 ? 'checked' : ''}}  name="logo_and_req_permission" id="logo_and_req_permission">

                                log and req permission (Service TH)
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="inventory_maintain_permission">
                                <input type="checkbox" {{$company->inventory_maintain_permission ==1 ? 'checked' : ''}} name="inventory_maintain_permission" id="inventory_maintain_permission">
                                Inventory Maintain permission (Store TH)
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="account_maintain_permission">  <input type="checkbox"  {{ $company->account_maintain_permission == 1 ? 'checked' : '' }} name="account_maintain_permission" id="account_maintain_permission">
                                Accounts maintain permission (ACCOUNTS TH)</label>
                        </div>
                        <div class="form-group">
                            <label for="access_all_call">  <input type="checkbox"  {{ $company->access_all_call == 1 ? 'checked' : '' }} name="access_all_call" id="access_all_call">
                                Access All Calls (Service TH)</label>
                        </div>
                        <div class="form-group">
                            <label for="access_all_call_visit_plan_without_call">  <input type="checkbox"  {{ $company->access_all_call_visit_plan_without_call == 1 ? 'checked' : '' }} name="access_all_call_visit_plan_without_call" id="access_all_call_visit_plan_without_call">
                                Access All Visit Plan (Without Call) (Merketing TH)</label>
                        </div>
                        <div class="form-group">
                            <label for="store_damage_product_assign_permission">  <input type="checkbox"  {{ $company->store_damage_product_assign_permission == 1 ? 'checked' : '' }} name="store_damage_product_assign_permission" id="store_damage_product_assign_permission">
                               Store Damage Product Assign Permission (Service TH)</label>
                        </div>
                        <div class="form-group">
                            <label for="active"> <input type="checkbox" {{$company->active ? 'checked' : ''}} name="active" id="active">
                                Active</label>
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
