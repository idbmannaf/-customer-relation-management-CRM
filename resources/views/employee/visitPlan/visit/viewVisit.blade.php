@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Create Visit
@endpush
@push('css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
    <style>
        .motherCard {
            position: relative;
        }

        .croseBtn {
            position: absolute;
            top: 1;
            right: 6px;
        }

        .service_log input.form-control {
            border: none;
            border-bottom: 1px solid;
            border-bottom-style: dotted;
            outline: none;
        }

        .service_log input.form-control:focus-visible {
            border: none !important;
            border-bottom: 1px solid !important;
            border-bottom-style: dotted !important;

            outline: none;
        }

        .item_bottom {

            font-weight: 500 !important;
        }

        .bg-head {
            background-color: #6c757d3d;
            color: black;
            margin: 10px 0px;
            padding: 3px;
            font-weight: 500;
        }

        .flex-auto {
            flex: auto;
        }

        .call_details {
            padding: 2px 5px;
            border: 1px solid #6c757d3d;
            font-weight: 600 !important;

        }

        table input {
            overflow: visible !important;
            width: 100% !important;
        }

        /* .product_description table, .product_description tr , .product_description th, .product_description td{
                                            border: 1px solid;
                                            border-collapse: collapse;
                                        } */

        /* .aiz-switch input:empty {
                                        height: 0;
                                        width: 0;
                                        overflow: hidden;
                                        position: absolute;
                                        opacity: 0;
                                    }
                                            .aiz-switch input:empty ~ span {
                                        display: inline-block;
                                        position: relative;
                                        text-indent: 0;
                                        cursor: pointer;
                                        -webkit-user-select: none;
                                        -moz-user-select: none;
                                        -ms-user-select: none;
                                        user-select: none;
                                        line-height: 24px;
                                        height: 21px;
                                        width: 40px;
                                        border-radius: 12px;
                                    } */

        table.table.myt td {
            border: navajowhite;
            padding-top: 0px;
        }
    </style>
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Create Visits Of {{ $visit_plan->date_time }}
                <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm">Back</a>
                @if ($visit_plan->customer_office_location_id)
                    <a href="javascript:void(0)" class="btn btn-danger">Already Checked</a>
                @else
                    <a href="{{ route('employee.customerVisited', $visit_plan) }}" class="btn btn-light btn-xs"
                        onclick="return confirm('are you sure? you checked This Customer?')">Checked In</a>
                @endif
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            @csrf
            @if ($visit_plan->service_type == 'service')
                <div class="card">
                    <div class="col-12 pb-3">
                        <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat"
                            class="btn btn-info">Customer
                            Details</a>
                        @if ($visit_plan->call)
                            <a href="" class="btn btn-success  float-right" data-toggle="modal"
                                data-target="#vp-{{ $visit_plan->id }}" data-whatever="@fat">Service Call
                                Details</a>
                        @endif
                    </div>


                    <div class="card-header bg-gray">Service Part</div>
                    <div class="card-body service_log">
                        <div class="row">
                            <div class="col-12 col-md-10">
                                <div class="row">
                                    <div class="col-12 col-md-6">

                                        <div class="row">
                                            <label class="col-12 col-md-5 item_bottom" for="complain_no">Complain
                                                No:</label>
                                            <div class="col-12 col-md-7 ">
                                                <div class="bdr">{{ $visit->id }}</div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="row">
                                            <label for="eng_name" class="col-12 col-md-5 item_bottom">Eng. /Tech.
                                                Name:</label>
                                            <div class="col-12 col-md-7">{{ $visit->employee->name }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-12 col-md-6 ">
                                        <div class="row">
                                            <label class="col-12 col-md-5 item_bottom" for="date">Date:</label>
                                            <div class="col-12 col-md-7">
                                                {{ \Carbon\Carbon::parse($visit->date_time)->format('Y-m-d') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 text-left">
                                        <div class="row">
                                            <label for="time" class="col-12 col-md-5 item_bottom">Time:</label>
                                            <div class="col-12 col-md-7">
                                                {{ \Carbon\Carbon::parse($visit->date_time)->format('H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-2 order-10">
                                <div class="d-flex justify-content-end">
                                    <img style="width: 100px;" src="{{ asset('img/orient.png') }}" alt=""
                                        srcset="">
                                </div>
                            </div>
                        </div>
                        <div class="bg-head">Client's lnformation</div>

                        <div class="client_information">
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="customer_name" class="item_bottom">Customer
                                            Name:</label>
                                        <div class="flex-auto pl-3">
                                            {{ $visit->customer->customer_name }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="ft_aym_no" class="item_bottom">FT/AYM
                                            No:</label>
                                        <div class="flex-auto pl-3">
                                            {{ $visit->ft_aym_no }}</div>
                                    </div>

                                </div>
                            </div>

                            <div class="address">
                                <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                    <label for="address" class="item_bottom">Addresss:</label>
                                    <div class="flex-auto pl-3">
                                        {{ $visit_plan->customer_address }}</div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="contact_person" class="item_bottom">Contact Person:</label>
                                        <div class="flex-auto pl-3">
                                            {{ $visit->customer->contact_person_name }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="mobile_number" class="item_bottom">Mobile Number:</label>
                                        <div class="flex-auto pl-3">
                                            {{ $visit->customer->mobile }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="call_details mb-2"> Call Description</div>
                        <div class="d-flex justify-content-between flex-wrap">
                            <label for="new_installation" class="d-flex align-items-center font-weight-normal">
                                <input type="checkbox" class="custom_checkbox" id="new_installation"
                                    value="new_installation"
                                    {{ $visit->call_description == 'new_installation' ? 'checked' : '' }}
                                    name="call_description[]">&nbsp;
                                New
                                Installation</label>

                            <label for="replacement" class="d-flex align-items-center font-weight-normal"> <input
                                    type="checkbox" class="custom_checkbox" id="replacement" value="replacement"
                                    value="replacement" {{ $visit->call_description == 'replacement' ? 'checked' : '' }}
                                    name="call_description[]">&nbsp; Replacement</label>

                            <label for="battery_replace" class="d-flex align-items-center font-weight-normal">
                                <input type="checkbox"
                                    {{ $visit->call_description == 'battery_replace' ? 'checked' : '' }}
                                    class="custom_checkbox" value="battery_replace" id="battery_replace"
                                    name="call_description[]">&nbsp;
                                Battery Replace</label>

                            <label for="service_maintaince" class="d-flex align-items-center font-weight-normal">
                                <input type="checkbox"
                                    {{ $visit->call_description == 'service_maintaince' ? 'checked' : '' }}
                                    class="custom_checkbox" value="service_maintaince" id="service_maintaince"
                                    name="call_description[]">&nbsp;
                                Service/Maintenance</label>


                        </div>
                        <div class="product_description">
                            <div class="bg-head">Product Description</div>
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
                                        </tr>
                                    </thead>
                                    <tbody class="sericeProductShow">
                                        @foreach ($visit->serviceProducts as $product)
                                            <tr>
                                                <td>{{ $product->device_name }}
                                                </td>
                                                <td>{{ $product->brand }}
                                                </td>
                                                <td>{{ $product->model_no }}</td>
                                                <td>{{ $product->service_volt }}</td>
                                                <td>{{ $product->capacity }}</td>
                                                <td>{{ $product->serial_no }}</td>
                                                <td>{{ $product->total_load }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

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
                                                @if ($visit->ventilation_system)
                                                    <span class="text-success">Yes</span>
                                                @else
                                                    <span class="text-danger">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($visit->air_condition)
                                                    <span class="text-success">Yes</span>
                                                @else
                                                    <span class="text-danger">No</span>
                                                @endif

                                            </td>
                                            <td>
                                                {{ $visit->room_temperature }}
                                            </td>
                                            <td>
                                                {{ $visit->ac_temperature }}
                                            </td>
                                            <td>
                                                {{ $visit->ac_run_time }}
                                            </td>
                                            <td>
                                                {{ $visit->other }}
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
                                                {{ $visit->imput_vat }}
                                            </td>
                                            <td>
                                                {{ $visit->charging_v }}
                                            </td>
                                            <td>
                                                {{ $visit->charging_amp }}
                                            </td>
                                            <td>
                                                {{ $visit->imput_freq }}
                                            </td>
                                            <td>
                                                {{ $visit->earthen_voltage }}
                                            </td>
                                            <td>
                                                {{ $visit->output_vac }}
                                            </td>
                                            <td>
                                                {{ $visit->output_freq }}
                                            </td>


                                            <td colspan="2">
                                                <table class="table myt">
                                                    <tr>
                                                        <td style="border-right:1px solid;">
                                                            {{ $visit->earthen_voltage_le }}
                                                        </td>
                                                        <td>
                                                            {{ $visit->earthen_voltage_ne }}
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
                                            <td>
                                                {{ $visit->battery_brand }}
                                            <td>
                                                {{ $visit->battery_amp }}
                                            <td>
                                                {{ $visit->battery_qty }}
                                            <td>
                                                {{ $visit->battery_time }}
                                            <td>
                                                {{ $visit->physical_condition }}
                                            <td colspan="2">
                                                {{ $visit->other_one }}</td>
                                            <td>
                                                {{ $visit->other_two }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th colspan="8" class="text-center">Maintenance</th>
                                        </tr>
                                        <tr>
                                            <td colspan="8">
                                                <table class="table">
                                                    <tr>
                                                        <td>
                                                            1. Motherboard
                                                        </td>
                                                        <td>

                                                            <label for=""> <input type="checkbox"
                                                                    {{ $visit->service_maintainces && $visit->service_maintainces->motherboard_checked ? 'checked' : '' }}></label>
                                                            checked

                                                        </td>
                                                        <td>

                                                            <label for=""> <input type="checkbox"
                                                                    {{ $visit->service_maintainces && $visit->service_maintainces->motherboard_cleaned ? 'checked' : '' }}></label>
                                                            Cleaned


                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            2. AC/DC Cable
                                                        </td>
                                                        <td>
                                                            <label for=""> <input type="checkbox"
                                                                    {{ $visit->service_maintainces->ac_dc_cable_checked ? 'checked' : '' }}></label>
                                                            checked

                                                        </td>
                                                        <td>
                                                            <label for=""> <input type="checkbox"
                                                                    {{ $visit->service_maintainces->ac_dc_cable_cleaned ? 'checked' : '' }}></label>
                                                            Cleaned


                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            2. Breaker
                                                        </td>
                                                        <td>
                                                            <label for=""> <input type="checkbox"
                                                                    {{ $visit->service_maintainces->breaker_checked ? 'checked' : '' }}></label>
                                                            checked


                                                        </td>
                                                        <td>
                                                            <label for=""> <input type="checkbox"
                                                                    {{ $visit->service_maintainces->breaker_cleaned ? 'checked' : '' }}></label>
                                                            Cleaned


                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            2. Socket
                                                        </td>
                                                        <td>
                                                            <label for=""> <input type="checkbox"
                                                                    {{ $visit->service_maintainces->socket_checked ? 'checked' : '' }}></label>
                                                            checked


                                                        </td>
                                                        <td>
                                                            <label for=""> <input type="checkbox"
                                                                    {{ $visit->service_maintainces->socket_cleaned ? 'checked' : '' }}></label>
                                                            Cleaned

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            2. Battert Ternubak
                                                        </td>
                                                        <td>
                                                            <label for=""> <input type="checkbox"
                                                                    {{ $visit->service_maintainces->battert_ernubak_checked ? 'checked' : '' }}></label>
                                                            checked


                                                        </td>
                                                        <td>
                                                            <label for=""> <input type="checkbox"
                                                                    {{ $visit->service_maintainces->battery_ernubak_cleaned ? 'checked' : '' }}></label>
                                                            Cleaned


                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="requirment">
                            <div class="bg-head">Requirements of Batteries/Spare parts to solve the problems</div>
                            <table class="table table-borderd table-sm text-nowrap">
                                <thead>
                                    <tr>
                                        <td width="5%">Sl</td>
                                        <td width="5%">Problem</td>
                                        <td width="65%">Product</td>
                                        <td width="20%">Quantity</td>
                                        <td width="10%">Unit</td>
                                    </tr>
                                </thead>
                                <tbody class="showServiceRequirements">
                                    @if (count($visit->service_requirment_batt_spear_parts) > 0)
                                        @foreach ($visit->service_requirment_batt_spear_parts as $serviceRequirement)
                                            <tr class="serReqSerial">
                                                <td width="5%" class="serialO">{{ $loop->index + 1 }}

                                                </td>
                                                <td width="50%">{{ $serviceRequirement->problem }}</td>
                                                <td width="50%">
                                                    {{ $serviceRequirement->product ? $serviceRequirement->product->name : '' }}
                                                </td>
                                                <td width="20%">{{ $serviceRequirement->quantity }}</td>
                                                <td width="10%">{{ $serviceRequirement->unit }}</td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="call_status">
                            <div class="bg-head">Call Status</div>
                            <div class="row">
                                {{-- <div class="col-12 col-md-3"> <label for="call_status1"> <input type="radio"
                                            name="call_status" id="call_status1"
                                            {{ $visit->call_status == 'completed' ? 'checked' : '' }} value="completed">
                                        Completed</label>
                                </div> --}}
                                <div class="col-12 col-md-3">
                                    <label for="call_status2"> <input type="radio" name="call_status"
                                            id="call_status2" {{ $visit->call_status == 'pending' ? 'checked' : '' }}
                                            value="pending">
                                        Pending</label>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="call_status4"> <input type="radio" {{ $visit->call_status == 'reviewed' ? 'checked' : '' }} name="call_status" id="call_status3"
                                            value="reviewed">Reviewed</label>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="call_status5"> <input type="radio" {{ $visit->call_status == 'done' ? 'checked' : '' }} name="call_status" id="call_status5"
                                            value="done"> Done</label>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="remarks pt-2">
                            <div class="d-flex justify-content-start flex-wrap">
                                <label for="remarks" class="d-flex align-items-center"> Remarks:</label>
                                <div class="flex-auto">
                                    <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                        </div> --}}
                        <div class="signature pt-3">
                            <table class="table table-bordered table-sm text-nowrap">
                                <tr>
                                    <td>Signature of Service ineer/Technician</td>
                                    <td>Customer's Signature</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-start flex-wrap">
                                            <label for="remarks" class="d-flex align-items-center">
                                                Signature:</label>
                                            <div class="flex-auto pl-3">
                                                {{ $visit->eng_signature }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start flex-wrap">
                                            <label for="remarks" class="d-flex align-items-center">
                                                Signature:</label>
                                            <div class="flex-auto pl-3">
                                                {{ $visit->customer_signature }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-start flex-wrap">
                                            <label for="eng_date" class="d-flex align-items-center"> Date:</label>
                                            <div class="flex-auto pl-3">
                                                {{ $visit->eng_date }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start flex-wrap">
                                            <label for="customer_date" class="d-flex align-items-center">
                                                Date:</label>
                                            <div class="flex-auto pl-3">
                                                {{ $visit->customer_date }}
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
                                            <div class="flex-auto pl-2">
                                                {{ $visit->seal }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="tagline">
                            Corporate Office: Concord Tower, Suit No. 1401, 113 Kazi Nazrul lslam Ave, Dhaka" Registered
                            Office: Motaleb
                            Tower, 8/2 Paribag, 1st Floor, Dhaka, Hotline: 09678221269 | Phone: 02-41031890
                        </div>

                    </div>
                </div>





            @endif



            {{-- Service End --}}

            {{-- Sales Start --}}
            @if ($visit_plan->service_type == 'sales')
                <div class="card">
                    <div class="card-header bg-gray">Sales Part</div>
                    {{-- <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Warranty</th>
                                    <th>Capacity</th>
                                    <th>Backup Time</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($visit->sales_items as $sales_item)
                                    <tr>
                                        <td>{{ $sales_item->product_name }}</td>
                                        <td>{{ $sales_item->product_warranty }}</td>
                                        <td>{{ $sales_item->product_capacity }}</td>
                                        <td>{{ $sales_item->product_backup_time }}</td>
                                        <td>{{ $sales_item->product_quantity }}</td>
                                        <td>{{ $sales_item->product_unit_price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right">Total Price</td>
                                    <td>{{ $visit->total_sales_price() }}</td>
                                </tr>
                            </tfoot>
                        </table>


                    </div> --}}
                </div>
            @endif
            {{-- @if ($visit_plan->service_type == 'delivery')
                    @include('employee.visitPlan.visit.part.delivery')
                @endif --}}
            @if ($visit_plan->service_type == 'collection')
                <div class="card">
                    <div class="card-header bg-gray">Collection Part</div>
                    @if ($invoice)
                    @include('employee.account.part.invoice_card')
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label for="payment_collection_date">Payment Collection Date: </label>
                                {{ $visit->payment_collection_date }}
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="payment_maturity_date">Payment Maturity Date: </label>
                                {{ $visit->payment_maturity_date }}
                            </div>
                            <div class="col-12">
                                <label for="collection_details" class="form-label">Collection Details: </label>
                                {{ $visit->collection_details }}
                            </div>
                            <div class="col-12">
                                <label for="collection_amount">Collection Amount: </label>
                                {{ $visit->collection_amount }}
                            </div>
                        </div>


                    </div>
                </div>
            @endif



            <div class="row">
                @if ($visit->files)
                    <div class="images">
                        <label for="">Attachment Files</label> <br>
                        @foreach ($visit->files as $attachment)
                            <a class="badge badge-success"
                                href="{{ route('imagecache', ['template' => 'original', 'filename' => $attachment->name]) }}">{{ $loop->index + 1 }}.
                                {{ $attachment->name }}</a><br>
                        @endforeach
                    </div>
                @endif

                <div class="remarks pt-2 col-12">
                    <div class="">
                        <label for="remarks" class="d-flex align-items-center"> Remarks:</label>
                        {{ $visit->remarks }}
                    </div>
                </div>
                <div class="purpose_of_visit pt-2 col-12">
                    <div class="">
                        <label for="remarks" class="d-flex align-items-center"> Purpose Of Visit:</label>
                        {{ $visit->purpose_of_visit }}
                    </div>
                </div>

            </div>

        </div>
        @if ($visit_plan->call)
            @include('global.callModal')
        @endif
        @include('global.customerModal')
    </div>
@endsection
