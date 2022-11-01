<style>
    .disable-design {
        background-color: transparent !important;
    }

    input[type=text],
    input[type=number],
    input[type=date] {
        display: block;
        width: 100%;
        border: none;
    }

    input:focus-visible {
        border: none !important;
        border-bottom: 1px solid !important;
        border-bottom-style: dotted !important;
        outline: none;
    }

    label:not(.form-check-label):not(.custom-file-label) {
        font-weight: normal;
        display: flex;
    }

    input.warr_sig {
        border-bottom: 1px solid !important;
    }

    input.warr_sig:focus-visible {
        border: none !important;
        border-bottom: 1px solid !important;
        outline: none;
    }
</style>
@php
    $warranty_claim = $requisition->warrantyClaim ;
@endphp
@if ($requisition->status == 'temp' || $requisition->status == 'pending')
<div class="card-body">
    <input type="hidden" name="warranty_claim_id" value="{{ $warranty_claim->id }}">
    <input type="hidden" name="product_id" id="product_id" value="{{ $warranty_claim->product_id ?? '' }}">
    <input type="hidden" name="warranty_period" value="{{ $warranty_claim->warranty_period }}" class="warranty_period">
    <div class="row">
        <div class="col-12 col-md-5 m-auto text-center" style="font-size: 15px; font-weight: 500">
            <p class="p-0 m-0">Orient BD Ltd</p>
            <p class="p-0 m-0">Corporate Office</p>
            <p class="p-0 m-0">Motaleb Tower, 1st Floor,8/2 Paribagh, Dhaka</p>
            <p class="p-0 m-0" style="text-decoration:underline; ">Warranty Requisition Form</p>
        </div>
    </div>
    <div class="row">
        {{-- {{dd($warranty_claim->product_id )}} --}}
        <div class="col-12 col-md-8 m-auto">
            <select name="product" id="product" class="form-control select2"
                data-url="{{ route('employee.visitToProductItemAjax') }}">
                <option value="">Select Product</option>
                @foreach ($visit->serviceProducts as $item)
                    <option {{ $warranty_claim->item_id == $item->id ? 'selected' : '' }}
                        value="{{ $item->id }}">{{ $item->product->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm text-nowrap">
            <tr>
                <td>S/L</td>
                <td>Customer:</td>
                <td colspan="5">{{ $requisition->customer->customer_name }}</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Address:</td>
                <td colspan="5">{{ $warranty_claim->customer_address }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Complain No:</td>
                <td colspan="2">{{ $warranty_claim->complain_no }}</td>
                <td>Complain Date</td>
                <td colspan="2">{{ $warranty_claim->complain_date }}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Sale Date:</td>
                <td><input type="date" name="sale_date" value="{{ old('sale_date') ?? $warranty_claim->sale_date }}">
                </td>
                <td>Invoice:</td>
                <td><input type="text" name="invoice" value="{{ old('invoice') ?? $warranty_claim->invoice }}"></td>
                <td>Warranty Period:</td>
                <td id="warranty_period">{{ $warranty_claim->warranty_period }}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Product Name:</td>
                <td colspan="4">
                    <input type="hidden" name="item_id" id="item_id" value="{{$warranty_claim->item_id}}">
                    <span id="product_name">{{ $warranty_claim->product_name }}</span>
                </td>
                <td><input type="number" required name="quantity" placeholder="Quantity"
                        value="{{ old('quantity') ?? $warranty_claim->quantity }}"></td>
            </tr>
            <tr>
                <td>5</td>
                <td>Old Product S/L:</td>
                <td colspan="5" id="old_product_serial_number">{{ $warranty_claim->old_product_serial_number }}</td>
            </tr>
            <tr>
                <td>6</td>
                <td>Warra. Provided For:</td>
                <td><label for="1st_time"><input type="radio" name="warranty_provide_for"
                            {{ $warranty_claim->warranty_provide_for == '1st_time' ? 'checked' : '' }}
                            value="1st_time" id="1st_time">1st Time</label></td>
                <td><label for="2nd_time"><input type="radio" name="warranty_provide_for"
                            {{ $warranty_claim->warranty_provide_for == '2nd_time' ? 'checked' : '' }}
                            value="2nd_time" id="2nd_time">2nd Time</label></td>
                <td><label for="3rd_time"><input type="radio" name="warranty_provide_for"
                            {{ $warranty_claim->warranty_provide_for == '3rd_time' ? 'checked' : '' }}
                            value="3rd_time" id="3rd_time">3rd Time</label></td>
                <td>Comment:</td>
                <td><input type="text" name="comment" value="{{ old('comment') ?? $warranty_claim->comment }}"></td>
            </tr>
            <tr>
                <td>7</td>
                <td>Reported Erg.Name:</td>
                <td colspan="2">{{ $requisition->employee ? $requisition->employee->name : '' }}</td>
                <td>Engr. Mobile No:</td>
                <td colspan="2"><input type="text" name="eng_mobile_number"
                        value="{{ old('eng_mobile_number') ?? $warranty_claim->eng_mobile_number }}"></td>
            </tr>
            <tr>
                <td>8</td>
                <td>Testing Report</td>
                <td colspan="5">

                    <table class="custom-t">
                        <tr>
                            <td>For Battery</td>
                            <td>Before Charge.V</td>
                            <td><input type="text" name="before_charge_v" value="{{ old('before_charge_v') ?? $warranty_claim->before_charge_v }}"></td>
                            <td>After Charge.V</td>
                            <td><input type="text" name="after_charge_v" value="{{ old('after_charge_v') ?? $warranty_claim->after_charge_v }}"></td>
                        </tr>
                        <tr>
                            <td>Testing Load With</td>
                            <td colspan="2"><input type="text" name="testing_load_with"
                                    value="{{ old('testing_load_with') ?? $warranty_claim->testing_load_with }}"></td>
                            <td>Backup Time:</td>
                            <td><input type="text" name="backup_time" value="{{ old('backup_time') ?? $warranty_claim->backup_time }}"></td>
                        </tr>
                        <tr>
                            <td>For UPS & Others</td>
                            <td colspan="4"><input type="text" name="for_ups_and_others"
                                    value="{{ old('for_ups_and_others') ?? $warranty_claim->for_ups_and_others }}" placeholder="Problem"></td>

                        </tr>
                    </table>
                </td>

            </tr>
            <tr>
                <td>9</td>
                <td>Current Due:</td>
                <td><input type="text" name="current_due"
                        value="{{ old('current_due') ?? $warranty_claim->current_due }}"></td>
                <td>Last Payment:</td>
                <td><input type="text" name="last_payment"
                        value="{{ old('last_payment') ?? $warranty_claim->last_payment }}"></td>
                <td>Date:</td>
                <td><input type="date" name="date" value="{{ old('date') ?? $warranty_claim->date }}"></td>
            </tr>
            <tr>
                <td>10</td>
                <td>Remarks:</td>
                <td colspan="5"><input type="text" name="remarks"
                        value="{{ old('remarks') ?? $warranty_claim->remarks }}"></td>
            </tr>
            <tr>
                <td>11</td>
                <td>Solution:</td>
                <td><label for="solution_zero_price"><input type="checkbox" name="solution[]" value="zero_price"
                            id="solution_zero_price"
                            {{ has_in_array($warranty_claim->solution, 'zero_price') ? 'checked' : '' }}>Zero
                        Price</label></td>
                <td colspan="2"><label for="solution_rechargeable_battery"><input type="checkbox"
                            name="solution[]"
                            {{ has_in_array($warranty_claim->solution, 'rechargeable_battery') ? 'checked' : '' }}
                            value="rechargeable_battery" id="solution_rechargeable_battery">Rechargeable
                        Battery</label></td>
                <td colspan="2"><label for="solution_reject"><input type="checkbox" name="solution[]"
                            value="reject" id="solution_reject"
                            {{ has_in_array($warranty_claim->solution, 'reject') ? 'checked' : '' }}> Reject</label>
                </td>
            </tr>
        </table>
    </div>
    <div class="container pt-5">
        <div class="d-flex justify-content-around text-center">
            <div>
                <div>
                    {{ $warranty_claim->preparedBy ? ($warranty_claim->preparedBy->employee ? $warranty_claim->preparedBy->employee->name : $warranty_claim->preparedBy->name) : '' }}
                </div>
                <div>Prepared By</div>
            </div>
            <div>
                <div>

                    {{ $warranty_claim->managerCordinetor ? ($warranty_claim->managerCordinetor->employee ? $warranty_claim->managerCordinetor->employee->name : $warranty_claim->managerCordinetor->name) : '' }}
                </div>
                <div>Manager/Coordinator</div>
            </div>
            <div>
                <div>

                    {{ $warranty_claim->accountDepartment ? ($warranty_claim->accountDepartment->employee ? $warranty_claim->accountDepartment->employee->name : $warranty_claim->accountDepartment->name) : '' }}
                </div>
                <div>Account's Dept.</div>
            </div>
            <div>
                <div>

                    {{ $warranty_claim->oparationManager ? ($warranty_claim->oparationManager->employee ? $warranty_claim->oparationManager->employee->name : $warranty_claim->oparationManager->name) : '' }}
                </div>
                <div>Oparation Manager</div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card-body">
    <input type="hidden" name="warranty_claim_id" value="{{ $warranty_claim->id }}">
    <div class="row">
        <div class="col-12 col-md-5 m-auto text-center" style="font-size: 15px; font-weight: 500">
            <p class="p-0 m-0">Orient BD Ltd</p>
            <p class="p-0 m-0">Corporate Office</p>
            <p class="p-0 m-0">Motaleb Tower, 1st Floor,8/2 Paribagh, Dhaka</p>
            <p class="p-0 m-0" style="text-decoration:underline; ">Warranty Requisition Form</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm text-nowrap">
            <tr>
                <td>S/L</td>
                <td>Customer:</td>
                <td colspan="5">{{ $requisition->customer->customer_name }}</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Address:</td>
                <td colspan="5">{{ $warranty_claim->customer_address }}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Complain No:</td>
                <td colspan="2">{{ $warranty_claim->complain_no }}</td>
                <td>Complain Date</td>
                <td colspan="2">{{ $warranty_claim->complain_date }}</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Sale Date:</td>
                <td>{{$warranty_claim->sale_date}}</td>
                <td>Invoice:</td>
                <td>{{$warranty_claim->invoice}}</td>
                <td>Warranty Period:</td>
                <td id="warranty_period">
                    {{ $warranty_claim->warranty_period }}

                </td>
            </tr>
            <tr>
                <td>4</td>
                <td>Product Name:</td>
                <td colspan="4">{{ $warranty_claim->product_name }}</td>
                <td> {{$warranty_claim->quantity}}</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Old Product S/L:</td>
                <td colspan="5" id="old_product_serial_number">{{ $warranty_claim->old_product_serial_number }}</td>
            </tr>
            <tr>
                <td>6</td>
                <td>Warra. Provided For:</td>
                <td><label for="1st_time"><input type="radio" name="warranty_provide_for"
                            {{ $warranty_claim->warranty_provide_for == '1st_time' ? 'selected' : '' }}
                            value="1st_time" id="1st_time">1st Time</label></td>
                <td><label for="2nd_time"><input type="radio" name="warranty_provide_for"
                            {{ $warranty_claim->warranty_provide_for == '2nd_time' ? 'selected' : '' }}
                            value="2nd_time" id="2nd_time">2nd Time</label></td>
                <td><label for="3rd_time"><input type="radio" name="warranty_provide_for"
                            {{ $warranty_claim->warranty_provide_for == '3rd_time' ? 'selected' : '' }}
                            value="3rd_time" id="3rd_time">3rd Time</label></td>
                <td>Comment:</td>
                <td>{{$warranty_claim->comment}}</td>
            </tr>
            <tr>
                <td>7</td>
                <td>Reported Erg.Name:</td>
                <td colspan="2">{{ $requisition->employee ? $requisition->employee->name : '' }}</td>
                <td>Engr. Mobile No:</td>
                <td colspan="2">{{$warranty_claim->eng_mobile_number}}</td>
            </tr>
            <tr>
                <td>8</td>
                <td>Testing Report</td>
                <td colspan="5">

                    <table class="custom-t">
                        <tr>
                            <td>For Battery</td>
                            <td>Before Charge.V</td>
                            <td>{{$warranty_claim->before_charge_v}}</td>
                            <td>After Charge.V</td>
                            <td>{{$warranty_claim->after_charge_v}}</td>
                        </tr>
                        <tr>
                            <td>Testing Load With</td>
                            <td colspan="2">{{$warranty_claim->testing_load_with}}</td>
                            <td>Backup Time:</td>
                            <td>{{$warranty_claim->backup_time}}</td>
                        </tr>
                        <tr>
                            <td>For UPS & Others</td>
                            <td colspan="4">{{$warranty_claim->for_ups_and_others}}</td>

                        </tr>
                    </table>
                </td>

            </tr>
            <tr>
                <td>9</td>
                <td>Current Due:</td>
                <td>{{$warranty_claim->current_due}}</td>
                <td>Last Payment:</td>
                <td>{{$warranty_claim->last_payment}}</td>
                <td>Date:</td>
                <td>{{$warranty_claim->date}}</td>
            </tr>
            <tr>
                <td>10</td>
                <td>Remarks:</td>
                <td colspan="5">{{$warranty_claim->remarks}}</td>
            </tr>
            <tr>
                <td>11</td>
                <td>Solution:</td>
                <td><label for="solution_zero_price"><input type="checkbox" name="solution[]" value="zero_price"
                            id="solution_zero_price"
                            {{ has_in_array($warranty_claim->solution, 'zero_price') ? 'checked' : '' }}>Zero
                        Price</label></td>
                <td colspan="2"><label for="solution_rechargeable_battery"><input type="checkbox"
                            name="solution[]"
                            {{ has_in_array($warranty_claim->solution, 'rechargeable_battery') ? 'checked' : '' }}
                            value="rechargeable_battery" id="solution_rechargeable_battery">Rechargeable
                        Battery</label></td>
                <td colspan="2"><label for="solution_reject"><input type="checkbox" name="solution[]"
                            value="reject" id="solution_reject"
                            {{ has_in_array($warranty_claim->solution, 'reject') ? 'checked' : '' }}> Reject</label>
                </td>
            </tr>
        </table>
    </div>
    <div class="container pt-5">
        <div class="d-flex justify-content-around text-center">
            <div>
                <div>
                    {{ $warranty_claim->preparedBy ? ($warranty_claim->preparedBy->employee ? $warranty_claim->preparedBy->employee->name : $warranty_claim->preparedBy->name) : '' }}
                </div>
                <div>Prepared By</div>
            </div>
            <div>
                <div>

                    {{ $warranty_claim->managerCordinetor ? ($warranty_claim->managerCordinetor->employee ? $warranty_claim->managerCordinetor->employee->name : $warranty_claim->managerCordinetor->name) : '' }}
                </div>
                <div>Manager/Coordinator</div>
            </div>
            <div>
                <div>

                    {{ $warranty_claim->accountDepartment ? ($warranty_claim->accountDepartment->employee ? $warranty_claim->accountDepartment->employee->name : $warranty_claim->accountDepartment->name) : '' }}
                </div>
                <div>Account's Dept.</div>
            </div>
            <div>
                <div>

                    {{ $warranty_claim->oparationManager ? ($warranty_claim->oparationManager->employee ? $warranty_claim->oparationManager->employee->name : $warranty_claim->oparationManager->name) : '' }}
                </div>
                <div>Oparation Manager</div>
            </div>
        </div>
    </div>
</div>
@endif


@push('js')
    <script>
        $('#product').change(function() {
            var item_id = $(this).val();
            var url = $(this).attr('data-url');
            var fullUrl = url + "?item=" + item_id;
            $.ajax({
                url: fullUrl,
                method: "GET",
                success: function(res) {
                    $('#item_id').val(res.item_id);
                    $('#product_id').val(res.product_id);
                    $('#product_name').text(res.product_name);
                    $('#old_product_serial_number').text(res.old_product_serial_number);
                    $('#warranty_period').text(res.warranty_period);
                    $('.warranty_period').val(res.warranty_period);
                }
            })
        })
    </script>
@endpush
