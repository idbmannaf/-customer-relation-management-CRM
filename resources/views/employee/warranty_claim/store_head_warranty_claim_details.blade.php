@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Requisition for Warranty Claim
@endpush

@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            Requisitions Of Warranty Claim
        </div>

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
                        <td>{{ $warranty_claim->sale_date }}</td>
                        <td>Invoice:</td>
                        <td>{{ $warranty_claim->invoice }}</td>
                        <td>Warranty Period:</td>
                        <td id="warranty_period">
                            {{ $warranty_claim->warranty_period }}

                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Product Name:</td>
                        <td colspan="4">{{ $warranty_claim->product_name }}</td>
                        <td> {{ $warranty_claim->quantity }}</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Old Product S/L:</td>
                        <td colspan="5" id="old_product_serial_number">{{ $warranty_claim->old_product_serial_number }}
                        </td>
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
                        <td>{{ $warranty_claim->comment }}</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Reported Erg.Name:</td>
                        <td colspan="2">{{ $requisition->employee ? $requisition->employee->name : '' }}</td>
                        <td>Engr. Mobile No:</td>
                        <td colspan="2">{{ $warranty_claim->eng_mobile_number }}</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Testing Report</td>
                        <td colspan="5">

                            <table class="custom-t table">
                                <tr>
                                    <td>For Battery</td>
                                    <td>Before Charge.V</td>
                                    <td>{{ $warranty_claim->before_charge_v }}</td>
                                    <td>After Charge.V</td>
                                    <td>{{ $warranty_claim->after_charge_v }}</td>
                                </tr>
                                <tr>
                                    <td>Testing Load With</td>
                                    <td colspan="2">{{ $warranty_claim->testing_load_with }}</td>
                                    <td>Backup Time:</td>
                                    <td>{{ $warranty_claim->backup_time }}</td>
                                </tr>
                                <tr>
                                    <td>For UPS & Others</td>
                                    <td colspan="4">{{ $warranty_claim->for_ups_and_others }}</td>

                                </tr>
                            </table>
                        </td>

                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Current Due:</td>
                        <td>{{ $warranty_claim->current_due }}</td>
                        <td>Last Payment:</td>
                        <td>{{ $warranty_claim->last_payment }}</td>
                        <td>Date:</td>
                        <td>{{ $warranty_claim->date }}</td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Remarks:</td>
                        <td colspan="5">{{ $warranty_claim->remarks }}</td>
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
                                    {{ has_in_array($warranty_claim->solution, 'reject') ? 'checked' : '' }}>
                                Reject</label>
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

    </div>
    <div class="row">
        <div class="col-12 col-md-4 m-auto">
            @if ($requisition->status == 'confirmed')
                <form
                    action="{{ route('employee.updateRequisition', ['visit' => $requisition->visit_id, 'type' => 'warranty_claim', 'requisition' => $requisition->id]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="status" value="reviewed">
                    <input type="hidden" name="warranty_claim_id" value="{{$warranty_claim->id}}">
                    <input type="submit" value="Reviewed" class="btn btn-success form-control">
                </form>
            @endif
        </div>
    </div>
@endsection


@push('js')
    <!-- summernote css/js -->
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@endpush
