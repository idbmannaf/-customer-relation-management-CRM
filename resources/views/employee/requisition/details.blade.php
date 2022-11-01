@extends('employee.layouts.employeeMaster')
@push('title')
    Requisition
@endpush
@push('css')
    <style>
        select#backUpType {
            background: none;
            border: none;
        }
    </style>

    <style>
        span.rq_title {
            font-size: 25px;
            font-weight: 700;
            text-transform: uppercase;
            background-color: gray;
            padding: 10px;
            border-radius: 20px;
        }

        input.custom.form-control {
            border: none;
            border-bottom: 1px solid;
            border-bottom-style: dotted;
            outline: none;
            /* text-align: center; */
        }

        input.custom.form-control:focus-visible {
            border: none !important;
            border-bottom: 1px solid !important;
            border-bottom-style: dotted !important;

            outline: none;
        }

        .item_bottom {
            display: flex;
            align-items: self-end;
            margin-top: 20px;
            font-weight: 500 !important;
        }

        .flex-auto {
            flex: auto;
            width: 120px;
        }

        @media print {
            .noPring {
                display: none;
            }

            .shadow {
                box-shadow: none !important;
            }

            span.rq_title {
                font-size: 25px;
                font-weight: 700;
                text-transform: uppercase;
                background-color: gray;
                padding: 10px;
                border-radius: 20px;
            }

        }
    </style>
    <link rel="stylesheet" href="{{ asset('back/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> Details Of Requisition {{ $requisition->id }} </div>
        </div>

        {{-- <div class="card-body shadow">
            @if ($requisition->visit->offer_quotation)
                @if ($requisition->visit->offer_quotation->customer_approved)
                    <div class="row">
                        <div class="col-12 col-md-8 m-auto bg-success h3" style="padding: 10px 5px;">
                            <a
                                href="{{ route('employee.customerOfferDetails', ['offer' => $requisition->visit->offer_quotation]) }}">Customer
                                Has Been Approved This Quotation</a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-12 col-md-8 m-auto bg-danger h3" style="padding: 10px 5px;">
                            <a
                                href="{{ route('employee.customerOfferDetails', ['offer' => $requisition->visit->offer_quotation]) }}">
                                Customer Does Not Approved This Quotation</a>

                        </div>
                    </div>
                @endif
            @endif
        </div> --}}


        <div class="card-body">
            @include('alerts.alerts')

            <form action="{{ route('employee.requisitionUpdate', ['requisition' => $requisition, 'type' => $type]) }}"
                method="post">
                @csrf
                @if ($type == 'spear_parts' || $type == 'product')
                    <style>
                        .disable-design {
                            background-color: transparent !important;
                        }
                    </style>
                    {{-- If status = temp or pending then show this --}}
                    @if ($requisition->status == 'pending')
                        <div class="card-body">
                            {{-- Header --}}
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <label class="col-12 col-md-6 font-weight-normal" for="invoice_no">Invoice
                                                    No:</label>
                                                <div class="col-12 col-md-6 ">{{ $requisition->id }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-8"><label class="font-weight-normal m_bottom"
                                                        for="sales_order_no">Complain
                                                        Sales Order No:</label></div>
                                                <div class="col-4"><input type="text" name="sales_order_no"
                                                        id="sales_order_no" class="form-control"
                                                        value="{{ $requisition->sales_order_no }}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 text-center">
                                    <span class="rq_title">Requisition Slip</span> <br>
                                    <span class="mt-2 d-inline-block">
                                        Corporate Head Office at Concord Tower, Suii No. 1401,
                                        113 Kazi Nazrul Islam Avenue, Dhaka, Bangladesh
                                    </span>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="d-flex justify-content-end">
                                        <img style="width: 150px;" src="{{ asset('img/orient.png') }}" alt=""
                                            srcset="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="party_name" class="item_bottom">Party Name:</label>
                                        <div class="flex-auto"><input type="text" name="party_name"
                                                class="custom form-control" value="{{ $requisition->party_name }}"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="date" class="item_bottom">Date:</label>
                                        <div class="flex-auto"><input type="date" name="date" required
                                                class="custom form-control text-center" value="{{ $requisition->date }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="mobile_number" class="item_bottom">Mobile Number:</label>
                                        <div class="flex-auto"><input type="text" name="mobile_number"
                                                class="custom form-control"
                                                value="{{ $requisition->mobile_number ? $requisition->mobile_number : ($requisition->customer ? $requisition->customer->mobile : '') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="sales_person" class="item_bottom">Sales Person:</label>
                                        <div class="flex-auto">

                                            <input type="text" name="sales_person"
                                                class="custom form-control text-center disable-design" disabled
                                                value="{{ $requisition->employee->name }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="delivery_address" class="item_bottom">Delivery Address:</label>
                                        <div class="flex-auto"><input type="text" name="delivery_address"
                                                class="custom form-control text-center"
                                                value="{{ $requisition->delivery_address }}"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="receiver_name" class="item_bottom">Receiver Name:</label>
                                        <div class="flex-auto"><input type="text" name="receiver_name"
                                                class="custom form-control" value="{{ $requisition->receiver_name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="mobile" class="item_bottom">Mobile:</label>
                                        <div class="flex-auto"><input type="text" name="mobile"
                                                class="custom form-control text-center"
                                                value="{{ $requisition->mobile }}"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>SL</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($visit->service_requirment_batt_spear_parts()->where('product_type', $type == 'spear_parts' ? 'spare_parts' : 'products')->get() as $product)
                                            @if ($checked_product = checked_if_have_this_product($product->product_id, $requisition->id))
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" checked class="checked_product"
                                                            name="products[{{ $product->product_id }}]"
                                                            onchange="update_price()" value="{{ $product->product_id }}">
                                                    </td>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $checked_product->product_name }}</td>
                                                    <td>{{ $checked_product->quantity }}
                                                        <input type="hidden" value="{{ $checked_product->quantity }}"
                                                            class="qty"
                                                            name="qty[{{ $checked_product->product_id }}]">
                                                    </td>
                                                    <td><input type="number" value="{{ $checked_product->unit_price }}"
                                                            name="unit_price[{{ $checked_product->product_id }}]"
                                                            class="unit_price"
                                                            oninput="product_update(this,'unit_price')">
                                                        <br>
                                                        <span class="text-danger priceError"></span>
                                                    </td>
                                                    <td class="total_price">{{ $checked_product->total_price }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>
                                                        {{-- {{dump(checked_if_have_this_product($product->product_id,$requisition->id))}} --}}
                                                        <input type="checkbox" class="checked_product"
                                                            name="products[{{ $product->product_id }}]"
                                                            onchange="update_price()" value="{{ $product->product_id }}">
                                                    </td>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $product->product ? $product->product->name : '' }}</td>
                                                    <td>{{ $product->quantity }}
                                                        <input type="hidden" value="{{ $product->quantity }}"
                                                            class="qty" name="qty[{{ $product->product_id }}]">
                                                    </td>
                                                    <td><input type="number"
                                                            value="{{ $product->product ? $product->product->unit_price : 0 }}"
                                                            name="unit_price[{{ $product->product_id }}]"
                                                            class="unit_price"
                                                            oninput="product_update(this,'unit_price')">
                                                        <br>
                                                        <span class="text-danger priceError"></span>
                                                    </td>
                                                    <td class="total_price">
                                                        {{ ($product->product ? $product->product->unit_price : 0) * $product->quantity }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total:
                                                <input type="hidden" value="{{ $requisition->req_product_total_price }}"
                                                    class="" id="final_price">
                                            </th>
                                            <td class="final_price">
                                                {{ $requisition->req_product_final_price }}

                                            </td>
                                        </tr>


                                    </tfoot>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="last_invoice_date" class="item_bottom">Last lnvoice Date:</label>
                                        <div class="flex-auto"><input type="date" name="last_invoice_date"
                                                class="custom form-control"
                                                value="{{ $requisition->last_invoice_date }}"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="last_payment" class="item_bottom">Last Payment:</label>
                                        <div class="flex-auto"><input type="text" name="last_payment"
                                                class="custom form-control text-center"
                                                value="{{ $requisition->last_payment }}"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="amount" class="item_bottom">Amount:</label>
                                        <div class="flex-auto"><input type="number" name="amount"
                                                class="custom form-control text-center"
                                                value="{{ $requisition->amount }}"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="present_dues_amount" class="item_bottom">Present Dues Amount:</label>
                                        <div class="flex-auto"><input type="number" name="present_dues_amount"
                                                class="custom form-control"
                                                value="{{ $requisition->present_dues_amount }}"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="payment_mode" class="item_bottom">Payment Mode:</label>
                                        <div class="flex-auto"><input type="text" name="payment_mode"
                                                class="custom form-control text-center"
                                                value="{{ $requisition->payment_mode }}"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="payment_date" class="item_bottom">Payment Date:</label>
                                        <div class="flex-auto"><input type="date" name="payment_date"
                                                class="custom form-control text-center"
                                                value="{{ $requisition->payment_date }}"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="commission_amount" class="item_bottom">Commissioin Amount (lf
                                            Required) :</label>
                                        <div class="flex-auto"><input type="text" name="commission_amount"
                                                class="custom form-control"
                                                value="{{ $requisition->commission_amount }}"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="any_special_note" class="item_bottom">Any Special Note</label>
                                        <div class="flex-auto"><input type="text" name="any_special_note"
                                                class="custom form-control text-center"
                                                value="{{ $requisition->any_special_note }}">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="amount_in_word" class="item_bottom">Amount in Word : </label>
                                        <div class="flex-auto"><input type="text" name="amount_in_word"
                                                class="custom form-control" value="{{ $requisition->amount_in_word }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between flex-wrap mt-3">
                                <div>
                                    <div>
                                        Signature
                                        of Sales Person
                                    </div>
                                    <div><input type="text" disabled
                                            value="{{ $requisition->customer->employee->name }}"
                                            class="custom form-control disable-design"></div>
                                </div>
                                <div>
                                    Reviewed By :
                                    <div>
                                        {{ $requisition->reviewedBy ? ($requisition->reviewedBy->employee ? $requisition->reviewedBy->employee->name : '') : '' }}
                                    </div>
                                </div>
                                <div>
                                    Approval
                                    <div>
                                        @if ($requisition->status == 'approved')
                                            <div class="text-center">
                                                <i class="fas fa-check fa-2x text-success"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>


                        </div>
                    @else
                        {{-- If status = not temp or pending then show this --}}
                        <div class="card-body" id="printArea">
                            {{-- Header --}}
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <label class="col-12 col-md-6 font-weight-normal" for="invoice_no">Invoice
                                                    No:</label>
                                                <div class="col-12 col-md-6 ">{{ $requisition->id }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-8"><label class="font-weight-normal m_bottom"
                                                        for="sales_order_no">Complain
                                                        Sales Order No:</label></div>
                                                <div class="col-4">{{ $requisition->sales_order_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 text-center">
                                    <span class="rq_title">Requisition Slip</span> <br>
                                    <span class="mt-2 d-inline-block">
                                        Corporate Head Office at Concord Tower, Suii No. 1401,
                                        113 Kazi Nazrul Islam Avenue, Dhaka, Bangladesh
                                    </span>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="d-flex justify-content-end">
                                        <img style="width: 150px;" src="{{ asset('img/orient.png') }}" alt=""
                                            srcset="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="party_name">Party Name:</label>
                                        <div class="flex-auto">{{ $requisition->party_name }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="date">Date:</label>
                                        <div class="flex-auto">{{ $requisition->date }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="mobile_number">Mobile Number:</label>
                                        <div class="flex-auto">
                                            {{ $requisition->mobile_number }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="sales_person">Sales Person:</label>
                                        <div class="flex-auto">
                                            {{ $requisition->customer->employee->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="delivery_address">Delivery Address:</label>
                                        <div class="flex-auto">{{ $requisition->delivery_address }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="receiver_name">Receiver Name:</label>
                                        <div class="flex-auto">{{ $requisition->receiver_name }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="mobile">Mobile:</label>
                                        <div class="flex-auto">{{ $requisition->mobile }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#SL</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisition->requisitionProducts as $product)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ $product->quantity }}
                                                    <input type="hidden" value="{{ $product->quantity }}"
                                                        class="qty" name="qty[{{ $product->product_id }}]">
                                                </td>
                                                <td>{{ $product->unit_price }} </td>
                                                <td class="total_price">
                                                    {{ $product->total_price }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-right">Total:</th>
                                                <td class="final_price">{{ $requisition->req_product_total_price }}</td>
                                            </tr>

                                        </tfoot>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="last_invoice_date">Last lnvoice Date:</label>
                                        <div class="flex-auto">{{ $requisition->last_invoice_date }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="last_payment">Last Payment:</label>
                                        <div class="flex-auto">{{ $requisition->last_payment }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="amount">Amount:</label>
                                        <div class="flex-auto">{{ $requisition->amount }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="present_dues_amount">Present Dues Amount:</label>
                                        <div class="flex-auto">{{ $requisition->present_dues_amount }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="payment_mode">Payment Mode:</label>
                                        <div class="flex-auto">{{ $requisition->payment_mode }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="payment_date">Payment Date:</label>
                                        <div class="flex-auto">{{ $requisition->payment_date }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-7">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="commission_amount">Commissioin Amount (lf Required) : </label>
                                        <div class="flex-auto">{{ $requisition->commission_amount }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="any_special_note">Any Special Note: </label>
                                        <div class="flex-auto">{{ $requisition->any_special_note }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="amount_in_word">Amount in Word : </label>
                                        <div class="flex-auto">{{ $requisition->amount_in_word }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between flex-wrap mt-3">
                                <div>
                                    <div>
                                        Signature
                                        of Sales Person
                                    </div>
                                    <div>{{ $requisition->employee->name }}</div>
                                </div>
                                <div>
                                    Reviewed By :
                                    <div>
                                        {{ $requisition->reviewedBy ? ($requisition->reviewedBy->employee ? $requisition->reviewedBy->employee->name : '') : '' }}
                                    </div>
                                </div>
                                <div>
                                    Approval
                                </div>
                            </div>


                        </div>
                    @endif

                    {{-- If Requisitio Type  == Inhouse Product --}}
                @elseif ($type == 'inhouse_product')
                    @if ($requisition->status == 'pending')
                        <div class="card-body">
                            {{-- Header --}}
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <label class="col-12 col-md-6 font-weight-normal"
                                                    for="complain_no">Invoice
                                                    No:</label>
                                                <div class="col-12 col-md-6 ">{{ $requisition->id }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-6"><label class="font-weight-normal m_bottom"
                                                        for="sales_order_no">Sales
                                                        Order No:</label></div>
                                                <div class="col-6"><input type="text" name="sales_order_no"
                                                        id="sales_order_no" class="form-control"
                                                        value="{{ $requisition->sales_order_no }}"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 text-center">
                                    <span class="rq_title">Requisition Slip</span> <br>
                                    <span class="mt-2 d-inline-block">
                                        Corporate Head Office at Concord Tower, Suii No. 1401,
                                        113 Kazi Nazrul Islam Avenue, Dhaka, BanEladesh
                                    </span>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="d-flex justify-content-end">
                                        <img style="width: 150px;" src="{{ asset('img/orient.png') }}" alt=""
                                            srcset="">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12 col-md-8">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="party_name" class="item_bottom">Party Name:</label>
                                        <div class="flex-auto"><input type="text" name="party_name"
                                                class="custom form-control" value="{{ $requisition->party_name }}"></div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="date" class="item_bottom">Date:</label>
                                        <div class="flex-auto"><input type="date" name="date"
                                                class="custom form-control text-center" value="{{ $requisition->date }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="receiver_name" class="item_bottom">Receiver Name:</label>
                                        <div class="flex-auto"><input type="text" name="receiver_name"
                                                class="custom form-control" value="{{ $requisition->receiver_name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="sales_person" class="item_bottom">Sales Person:</label>
                                        <div class="flex-auto">
                                            <input type="text" name="sales_person"
                                                class="custom form-control text-center disable-design" disabled
                                                value="{{ $requisition->employee->name }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>SL</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($visit->service_requirment_batt_spear_parts as $product)
                                            @if ($checked_product = checked_if_have_this_product($product->product_id, $requisition->id))
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" checked class="checked_product"
                                                            name="products[{{ $product->product_id }}]"
                                                            onchange="update_price()" value="{{ $product->product_id }}">
                                                    </td>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $checked_product->product_name }}</td>
                                                    <td>{{ $checked_product->quantity }}
                                                        <input type="hidden" value="{{ $checked_product->quantity }}"
                                                            class="qty"
                                                            name="qty[{{ $checked_product->product_id }}]">
                                                    </td>
                                                    <td><input type="number" value="{{ $checked_product->unit_price }}"
                                                            name="unit_price[{{ $checked_product->product_id }}]"
                                                            class="unit_price"
                                                            oninput="product_update(this,'unit_price')">
                                                        <br>
                                                        <span class="text-danger priceError"></span>
                                                    </td>
                                                    <td class="total_price">{{ $checked_product->total_price }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>
                                                        {{-- {{dump(checked_if_have_this_product($product->product_id,$requisition->id))}} --}}
                                                        <input type="checkbox" class="checked_product"
                                                            name="products[{{ $product->product_id }}]"
                                                            onchange="update_price()" value="{{ $product->product_id }}">
                                                    </td>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $product->product ? $product->product->name : '' }}</td>
                                                    <td>{{ $product->quantity }}
                                                        <input type="hidden" value="{{ $product->quantity }}"
                                                            class="qty" name="qty[{{ $product->product_id }}]">
                                                    </td>
                                                    <td><input type="number"
                                                            value="{{ $product->product ? $product->product->unit_price : 0 }}"
                                                            name="unit_price[{{ $product->product_id }}]"
                                                            class="unit_price"
                                                            oninput="product_update(this,'unit_price')">
                                                        <br>
                                                        <span class="text-danger priceError"></span>
                                                    </td>
                                                    <td class="total_price">
                                                        {{ ($product->product ? $product->product->unit_price : 0) * $product->quantity }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total:</th>
                                            <td class="final_price">{{ $requisition->req_product_final_price }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>


                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="amount_in_word" class="item_bottom">Amount in Word : </label>
                                        <div class="flex-auto"><input type="text" name="amount_in_word"
                                                class="custom form-control" value="{{ $requisition->amount_in_word }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between flex-wrap mt-3">
                                <div>
                                    <div>
                                        Remarks
                                    </div>
                                    <div><input type="text" class="custom form-control" name="remarks"
                                            value="{{ $requisition->remarks }}"></div>
                                </div>
                                <div>
                                    Approval
                                </div>
                            </div>


                        </div>
                    @else
                        <div class="card-body">
                            {{-- Header --}}
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <label class="col-12 col-md-6 font-weight-normal"
                                                    for="complain_no">Invoice
                                                    No:</label>
                                                <div class="col-12 col-md-6 ">{{ $requisition->id }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-6"><label class="font-weight-normal m_bottom"
                                                        for="sales_order_no">Sales
                                                        Order No:</label></div>
                                                <div class="col-6">{{ $requisition->sales_order_no }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 text-center">
                                    <span class="rq_title">Requisition Slip</span> <br>
                                    <span class="mt-2 d-inline-block">
                                        Corporate Head Office at Concord Tower, Suii No. 1401,
                                        113 Kazi Nazrul Islam Avenue, Dhaka, BanEladesh
                                    </span>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="d-flex justify-content-end">
                                        <img style="width: 150px;" src="{{ asset('img/orient.png') }}" alt=""
                                            srcset="">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12 col-md-8">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="party_name" class="">Party Name:</label>
                                        <div class="flex-auto">{{ $requisition->party_name }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="date" class="">Date:</label>
                                        <div class="flex-auto">{{ $requisition->date }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="receiver_name" class="=">Receiver Name:</label>
                                        <div class="flex-auto">{{ $requisition->receiver_name }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="d-flex justify-content-start flex-wrap" style="flex: 0 1;">
                                        <label for="sales_person" class="">Sales Person:</label>
                                        <div class="flex-auto">{{ $requisition->employee->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#SL</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisition->requisitionProducts as $product)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $product->product_name }}</td>
                                                <td>{{ $product->quantity }}
                                                    <input type="hidden" value="{{ $product->quantity }}"
                                                        class="qty" name="qty[{{ $product->product_id }}]">
                                                </td>
                                                <td>{{ $product->unit_price }} </td>
                                                <td class="total_price">
                                                    {{ $product->total_price }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">Total:</th>
                                            <td class="final_price">{{ $requisition->req_product_final_price }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>


                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start flex-wrap">
                                        <label for="amount_in_word" class="">Amount in Word : </label>
                                        <div class="flex-auto">{{ $requisition->amount_in_word }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between flex-wrap mt-3">
                                <div>
                                    <div>
                                        Remarks
                                    </div>
                                    <div>{{ $requisition->remarks }}</div>
                                </div>
                                <div>
                                    Approval
                                    @if ($requisition->status == 'approved')
                                        <div class="text-center">
                                            <i class="fas fa-check fa-2x text-success"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>


                        </div>
                    @endif

                @endif

                @if ($employee = auth()->user()->employee)
                    @if ($employee->company->logo_and_req_permission ||
                        $employee->company->store_damage_product_assign_permission ||
                        $employee->company->access_all_call)
                        <div class="row noPring">
                            <div class="col-12 col-md-3 m-auto">
                                @if ($requisition->status == 'pending')
                                    <input type="hidden" name="status" value="reviewed">
                                    <input type="submit" name="submitName" value="Reviewed"
                                        class="btn btn-warning form-control">
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

            </form>

        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).on('input', '.unit_price', function() {
            update_price();
        })

        function product_update(e, type) {
            var that = $(e);
            var quantity = that.closest('tr').find('.qty').val();
            var unit_price = that.val();
            if (unit_price < 0) {
                that.val(0);
                return;
            }
            that.closest('tr').find('.total_price').text(quantity * unit_price);
        }

        function update_price() {

            var checked_items = $('.checked_product');
            var total_price = 0;
            checked_items.map((index, element) => {
                var event = $(element);

                if (event.is(":checked")) {
                    total_price += Number(event.closest('tr').find('.total_price').text());
                }
            })
            $('.final_price').text(total_price);
            $('#final_price').val(total_price);
            $("#service_charge").trigger('input')
        }
    </script>
    <script>
        $(document).on('input', '#service_charge', function() {

            var final_price = Number($("#final_price").val());
            var service_charge = Number($(this).val());
            var grand_total = final_price + service_charge;
            $('.grand_total').text(grand_total)
        })
    </script>
@endpush
