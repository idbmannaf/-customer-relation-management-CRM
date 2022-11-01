<div class="card">

    <div class="card-header bg-gray">Service Part</div>
    <div class="card-body service_log">
        @if (Agent::isDesktop())
            <div class="row">
                <div class="col-12 col-md-10">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <label class="col-12 col-md-5 font-weight-normal" for="complain_no">Complain
                                    No:</label>
                                <div class="col-12 col-md-7 ">{{ $visit->id }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <label for="eng_name" class="col-12 col-md-5 font-weight-normal">Eng. /Tech.
                                    Name:</label>
                                <div class="col-12 col-md-7">{{ $visit_plan->employee->name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-12 col-md-6 ">
                            <div class="row">
                                <label class="col-12 col-md-5 item_bottom" for="date">Date:</label>
                                <div class="col-12 col-md-7"><input type="date" name="date" class="form-control"
                                        value="{{ old('date') ?? \Carbon\Carbon::now()->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 text-left">
                            <div class="row">
                                <label for="time" class="col-12 col-md-5 item_bottom">Time:</label>
                                <div class="col-12 col-md-7"><input name="time" type="time" class="form-control"
                                        value="{{ old('time') ?? \Carbon\Carbon::now()->format('H:i') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-2 order-10">
                    <div class="d-flex justify-content-end">
                        <img style="width: 100px;" src="{{ asset('img/orient.png') }}" alt="" srcset="">
                    </div>
                </div>
            </div>
            <div class="bg-head">Client's lnformation</div>

            <div class="client_information">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                            <label for="customer_name" class="font-weight-normal">Customer
                                Name:</label>
                            <div class="flex-auto pl-3">{{ $visit_plan->customer->customer_name }}
                            </div>
                        </div>

                    </div>
                    <div class="col-12 col-md-5">
                        <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                            <label for="ft_aym_no" class="item_bottom">FT/AYM
                                No:</label>
                            <div class="flex-auto"><input type="text" name="ft_aym_no" class="form-control"
                                    value="{{ old('ft_aym_no') ?? $visit->ft_aym_no }}"></div>
                        </div>

                    </div>
                </div>

                <div class="address">
                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                        <label for="address" class="font-weight-normal">Addresss:</label>
                        <div class="flex-auto pl-3">{{ $visit->customer_address }}</div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-md-7">
                        <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                            <label for="contact_person" class="font-weight-normal">Contact Person:</label>
                            <div class="flex-auto pl-3"> {{ $visit->contact_person_name }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                            <label for="mobile_number" class="font-weight-normal item_bottom">Mobile Number:</label>
                            <div class="flex-auto pl-3">
                                <input type="text" name="mobile_number" class="form-control"
                                    value="{{ $visit->mobile_number }}">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div>
            <div class="call_details mb-2"> Call Description</div>
            <div class="text-dagner">
                @error('call_description')
                    {{ $message }}
                @enderror
            </div>
            <div class="d-flex justify-content-between flex-wrap">
                <label for="new_installation" class="d-flex align-items-center font-weight-normal">
                    <input type="checkbox" class="custom_checkbox" id="new_installation"
                        {{ has_in_array($visit->call_description, 'new_installation') ? 'checked' : '' }}
                        value="new_installation" name="call_description[]">&nbsp;
                    New
                    Installation</label>

                <label for="replacement" class="d-flex align-items-center font-weight-normal"> <input type="checkbox"
                        class="custom_checkbox" id="replacement"
                        {{ has_in_array($visit->call_description, 'replacement') ? 'checked' : '' }}
                        value="replacement" value="replacement" name="call_description[]">&nbsp; Replacement</label>

                <label for="battery_replace" class="d-flex align-items-center font-weight-normal">
                    <input type="checkbox" class="custom_checkbox"
                        {{ has_in_array($visit->call_description, 'battery_replace') ? 'checked' : '' }}
                        value="battery_replace" id="battery_replace" name="call_description[]">&nbsp;
                    Battery Replace</label>

                <label for="service_maintaince" class="d-flex align-items-center font-weight-normal">
                    <input type="checkbox" class="custom_checkbox"
                        {{ has_in_array($visit->call_description, 'service_maintaince') ? 'checked' : '' }}
                        value="service_maintaince" id="service_maintaince" name="call_description[]">&nbsp;
                    Service/Maintenance</label>


            </div>
            <div class="product_description">
                <div class="bg-head">Product Description</div>
                <div class="row py-3">
                    <div class="col-12 col-md-4">
                        <label for="category">Select Category</label>
                        <select name="category" id="category"
                            data-url="{{ route('employee.categoryToServiceProduct', ['visit_plan' => $visit_plan->id, 'visit' => $visit->id]) }}"
                            class="form-control select2">
                            <option value="" selected disabled>Select Category</option>
                            <option value="0">All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-8 showProduct">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-nowrap">
                        <thead>
                            <tr>
                                <td>Device Name</td>
                                <td>Brand</td>
                                <td>Model No.</td>
                                <td>System Volt</td>
                                <td>Capacity</td>
                                <td>Serial No.</td>
                                <td>Total Load</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody class="sericeProductShow">
                            @foreach ($visit->serviceProducts as $product)
                                @include('employee.visitPlan.visit.part.edit.sericeProductShow')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if (Agent::isDesktop())
            <div class="room_atmosphere">
                <div class="bg-head">Room Atmosphere</div>
                <div class="table-responsive ">
                    <table class="table table-bordered table-sm text-nowrap">
                        <thead>
                            <tr>
                                <td>Ventilation System</td>
                                <td>Air-Condition</td>
                                <td>Room Temperature</td>
                                <td>A/C Temperature</td>
                                <td>A/C Run Time/day</td>
                                <td>Other</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1"
                                            name="ventilation_system"
                                            {{ old('ventilation_system') || $visit->ventilation_system ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customSwitch1">Yes/No</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                            {{ old('air_condition') || $visit->ventilation_system ? 'checked' : '' }}
                                            class="custom-control-input" id="customSwitch2" name="air_condition">
                                        <label class="custom-control-label" for="customSwitch2">Yes/No</label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="room_temperature"
                                        value="{{ old('room_temperature') ?? $visit->room_temperature }}"
                                        id="room_temperature">
                                </td>
                                <td>
                                    <input type="text" name="ac_temperature"
                                        value="{{ old('ac_temperature') ?? $visit->ac_temperature }}"
                                        id="ac_temperature">
                                </td>
                                <td>
                                    <input type="text" name="ac_run_time"
                                        value="{{ old('ac_run_time') ?? $visit->ac_run_time }}" id="ac_run_time">
                                </td>
                                <td>
                                    <input type="text" name="other" value="{{ old('other') ?? $visit->other }}"
                                        id="other">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="service_description">
                <div class="bg-head">Service Description</div>
                <div class="table-responsive ">
                    <table class="table table-bordered table-sm text-nowrap">
                        <thead>
                            <tr>
                                <th colspan="5" class="text-center">AC Mode</th>

                                <th colspan="3" class="text-center">lnverter Mode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Imput (VAT)</td>
                                <td>Charging (V)</td>
                                <td>Charging (Amp)</td>
                                <td>Imput Freq. (Hz)</td>
                                <td>Earthen Voltage</td>
                                <td>Output(VAC)</td>
                                <td>Output(Hz)</td>
                                <td colspan="2">
                                    Earthen Voltage
                                    <table class="table myt2">
                                        <tr>
                                            <td>L-E</td>
                                            <td>N-E</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="imput_vat"
                                        value="{{ old('imput_vat') ?? $visit->imput_vat }}" id="imput_vat">
                                </td>
                                <td>
                                    <input type="text" name="charging_v"
                                        value="{{ old('charging_v') ?? $visit->charging_v }}" id="charging_v">
                                </td>
                                <td>
                                    <input type="text" name="charging_amp"
                                        value="{{ old('charging_amp') ?? $visit->charging_amp }}" id="charging_amp">
                                </td>
                                <td>
                                    <input type="text" name="imput_freq"
                                        value="{{ old('imput_freq') ?? $visit->imput_freq }}" id="imput_freq">
                                </td>
                                <td>
                                    <input type="text" name="earthen_voltage"
                                        value="{{ old('earthen_voltage') ?? $visit->earthen_voltage }}"
                                        id="earthen_voltage">
                                </td>
                                <td>
                                    <input type="text" name="output_vac"
                                        value="{{ old('output_vac') ?? $visit->output_vac }}" id="output_vac">
                                </td>
                                <td>
                                    <input type="text" name="output_freq"
                                        value="{{ old('output_freq') ?? $visit->output_freq }}" id="output_freq">
                                </td>


                                <td colspan="2">
                                    <table class="table myt">
                                        <tr>
                                            <td>
                                                <input type="text"
                                                    value="{{ old('earthen_voltage_le') ?? $visit->earthen_voltage_le }}"
                                                    name="earthen_voltage_le">
                                            </td>
                                            <td>
                                                <input type="text"
                                                    value="{{ old('earthen_voltage_ne') ?? $visit->earthen_voltage_ne }}"
                                                    name="earthen_voltage_ne">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="8" class="text-center">Battery lnformation</th>
                            </tr>
                            <tr>
                                <td>Battery Brand</td>
                                <td>Battery (Amp)</td>
                                <td>Battery Qty.</td>
                                <td>Backup Time</td>
                                <td>Physical Condition</td>
                                <td colspan="2">&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><input type="text" value="{{ old('battery_brand') ?? $visit->battery_brand }}"
                                        name="battery_brand"></td>
                                <td><input type="text" value="{{ old('battery_amp') ?? $visit->battery_amp }}"
                                        name="battery_amp"></td>
                                <td><input type="number" value="{{ old('battery_qty') ?? $visit->battery_qty }}"
                                        name="battery_qty"></td>
                                <td><input type="text" value="{{ old('battery_time') ?? $visit->battery_time }}"
                                        name="battery_time"></td>
                                <td><input type="text"
                                        value="{{ old('physical_condition') ?? $visit->physical_condition }}"
                                        name="physical_condition"></td>
                                <td colspan="2"><input type="text"
                                        value="{{ old('other_one') ?? $visit->other_one }}" name="other_one"></td>
                                <td><input type="text" value="{{ old('other_two') ?? $visit->other_two }}"
                                        name="other_two"></td>
                            </tr>

                            <tr>
                                <th colspan="8" class="text-center">Maintenance</th>
                            </tr>
                            <tr>
                                <th colspan="8">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                1. Motherboard
                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="motherboard_checked" name="motherboard_checked"
                                                        {{ $visit->service_maintainces && $visit->service_maintainces->motherboard_checked ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="motherboard_checked">Checked</label>
                                                </div>

                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="motherboard_cleaned" name="motherboard_cleaned"
                                                        {{ $visit->service_maintainces && $visit->service_maintainces->motherboard_cleaned ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="motherboard_cleaned">Cleaned</label>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                2. AC/DC Cable
                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="ac_dc_able_checked" name="ac_dc_able_checked"
                                                        {{ $visit->service_maintainces && $visit->service_maintainces->ac_dc_cable_checked ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="ac_dc_able_checked">Checked</label>
                                                </div>

                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="ac_dc_able_cleaned" name="ac_dc_able_cleaned"
                                                        {{ $visit->service_maintainces && $visit->service_maintainces->ac_dc_cable_cleaned ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="ac_dc_able_cleaned">Cleaned</label>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                3. Breaker
                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="breaker_checked" name="breaker_checked"
                                                        {{ $visit->service_maintainces && $visit->service_maintainces->breaker_checked ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="breaker_checked">Checked</label>
                                                </div>

                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="breaker_cleaned" name="breaker_cleaned"
                                                        {{ $visit->service_maintainces && $visit->service_maintainces->breaker_cleaned ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="breaker_cleaned">Cleaned</label>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                4. Socket
                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="socket_checked" name="socket_checked"
                                                        {{ $visit->service_maintainces && $visit->service_maintainces->socket_checked ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="socket_checked">Checked</label>
                                                </div>

                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="socket_cleaned" name="socket_cleaned"
                                                        {{ $visit->service_maintainces && $visit->service_maintainces->socket_cleaned ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="socket_cleaned">Cleaned</label>
                                                </div>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                5. Battert Ternubak
                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="battery_ernubak_checked" name="battert_ernubak_checked"
                                                        {{ $visit->service_maintainces && $visit->service_maintainces->battery_ernubak_checked ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="battery_ernubak_checked">Checked</label>
                                                </div>

                                            </td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="battery_ernubak_cleaned" name="battert_ernubak_cleaned"
                                                        {{ $visit->service_maintainces && $visit->service_maintainces->battery_ernubak_cleaned ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="battery_ernubak_cleaned">Cleaned</label>
                                                </div>

                                            </td>
                                        </tr>
                                    </table>
                                </th>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        <div class="" style="border: 2px solid red;">
            <div class="requirment">
                <div class=" @if (Agent::isMobile()) bg-success p-2 @else bg-head  @endif " >Requirements of Batteries/Spare parts to solve the problems</div>
                <div class="table-responsive">
                    <table class="table table-borderd table-sm text-nowrap">
                        @if (Agent::isDesktop())
                        <thead>
                            <tr>
                                {{-- <td width="5%">Sl</td> --}}
                                <td width="30%">Problem</td>
                                <td width="20%">Category</td>
                                <td width="30%">Req. Spear Parts</td>
                                <td width="10%">Quantity</td>
                                <td width="15%">Unit</td>
                                <td width="5%">Action</td>
                            </tr>
                        </thead>
                        @endif
                        <tbody class="showServiceRequirements">
                            @if (count($visit_plan->service_requirment_batt_spear_parts) > 0)
                                @foreach ($visit_plan->service_requirment_batt_spear_parts as $serviceRequirement)
                                    @include('employee.visitPlan.visit.ajax.requireServiceAjax')
                                @endforeach

                            @endif

                            <input type="hidden" id="total_service_products"
                                value="{{ count(
                                    $visit_plan->service_requirment_batt_spear_parts()->where('visit_id', null)->get(),
                                ) }}">
                            <tr>
                                {{-- <td></td> --}}
                                <td><input type="text" name="problem" class="problem form-control"> <br>
                                    <span class="text-danger problemError"></span>
                                </td>
                                <td><select name="spear_part_cat" id="spear_part_cat"
                                        class="spear_part_cat form-control">
                                        <option value="">Select Type</option>
                                        <option value="spare_parts">Spare Parts</option>
                                        <option value="product">Product</option>
                                    </select></td>
                                <td class="showProducts">
                                    <span class="text-danger desError"></span>
                                </td>
                                <td><input type="number" name="quantity" class="quantity form-control"> <br>
                                    <span class="text-danger qtyError"></span>
                                </td>
                                <td><input type="text" name="unit" class="unit form-control"> <br>
                                    <span class="text-danger unitError"></span>
                                </td>
                                <td><a href="{{ route('employee.addRequirementsOfBattAndSpearPartAjax', ['visit_plan' => $visit_plan]) }}"
                                        class="btn btn-sm btn-success addRequre @if(Agent::isMobile()) form-control @endif"><i class="fas fa-plus"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="call_status">
            <div class="bg-head">Call Status</div>
            <div class="row">

                <div class="col-12 col-md-3">
                    <label for="call_status2"> <input type="radio"
                            {{ $visit->call_status == 'pending' ? 'checked' : '' }} name="call_status"
                            id="call_status2" value="pending"> Pending</label>
                </div>
                @if (auth()->user()->employee->team_admin)
                    <div class="col-12 col-md-3">
                        <label for="call_status3"> <input type="radio"
                                {{ $visit->call_status == 'reviewed' ? 'checked' : '' }} name="call_status"
                                id="call_status3" value="reviewed">Reviewed</label>
                    </div>
                @endif
                <div class="col-12 col-md-3">
                    <label for="call_status5"> <input type="radio"
                            {{ $visit->call_status == 'done' ? 'checked' : '' }} name="call_status" id="call_status5"
                            value="done"> Done</label>
                </div>

            </div>
        </div>

        <div class="signature pt-3">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <tr>
                        <td>Signature of Service ineer/Technician</td>
                        <td>Customer's Signature</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex justify-content-start flex-wrap">
                                <label for="remarks" class="d-flex align-items-center"> Signature:</label>
                                <div class="flex-auto">
                                    <input type="text" name="eng_signature" value="{{ $visit->eng_signature }}">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-start flex-wrap">
                                <label for="remarks" class="d-flex align-items-center"> Signature:</label>
                                <div class="flex-auto">
                                    <input type="text" name="customer_signature"
                                        value="{{ $visit->customer_signature }}">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex justify-content-start flex-wrap">
                                <label for="eng_date" class="d-flex align-items-center"> Date:</label>
                                <div class="flex-auto">
                                    <input type="date" name="eng_date" id="eng_date"
                                        value="{{ $visit->eng_date }}">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-start flex-wrap">
                                <label for="customer_date" class="d-flex align-items-center"> Date:</label>
                                <div class="flex-auto">
                                    <input type="date" name="customer_date" id="customer_date"
                                        value="{{ $visit->customer_date }}">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                        <td>
                            <div class="d-flex justify-content-start flex-wrap">
                                <label for="seal" class="d-flex align-items-center"> Seal:</label>
                                <div class="flex-auto">
                                    <input type="text" name="seal" id="seal"
                                        value="{{ $visit->seal }}">
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="tagline">
            Corporate Office: Concord Tower, Suit No. 1401, 113 Kazi Nazrul lslam Ave, Dhaka" Registered Office: Motaleb
            Tower, 8/2 Paribag, 1st Floor, Dhaka, Hotline: 09678221269 | Phone: 02-41031890
        </div>

    </div>
</div>


@push('js')
    <script>
        $(document).on('change', '.spear_part_cat', function() {

            var that = $(this);
            if (that.val()) {
                if (that.val() == 'spare_parts') {
                    var html = `<select id="${that.val()+Math.random()}" name="product_id"
    class="form-control user-select service_product"
    data-placeholder="Product Name / Model"
    data-ajax-url="{{ route('employee.productAllAjax', ['type' => 'spare_parts']) }}"
    data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200"
    style="">
</select>`;
                } else if (that.val() == 'product') {
                    var html = `<select id="${that.val()+Math.random()}{{ rand(10000, 9999) }}" name="product_id"
    class="form-control user-select service_product"
    data-placeholder="Product Name / Model"
    data-ajax-url="{{ route('employee.productAllAjax', ['type' => 'products']) }}"
    data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200"
    style="">
</select>`;
                }


                that.closest('tr').find('.showProducts').html(html);
                $('.service_product').select2({
                    theme: 'bootstrap4',
                    closeOnSelect: true,
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
                                obj.text = obj.name + "(" + obj.model + ")";
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
            }
        })
    </script>
@endpush
