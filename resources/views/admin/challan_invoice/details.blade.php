@extends('admin.layouts.adminMaster')
@push('title')
    {{ $type }} Details
@endpush
@push('css')
    <style>
        .row {
            padding-top: 2px;
        }

        tfoot tr,
        tfoot td,
        tfoot th {
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;

        }

        @media print {

            tfoot tr,
            tfoot td,
            tfoot th {
                border-bottom: none !important;
                border-left: none !important;
                border-right: none !important;

            }
        }
    </style>
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            Details Of {{ $type }} no : {{ $type == 'challan' ? $data->challan_no : $data->invoice_no }}
            <a href="" onclick="return printDiv('printArea');" class="btn btn-danger ">Print</a>
        </div>
        <script type="text/javascript">
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
            }
        </script>
        @if ($type == 'invoice')
            <div class="card-body" id="printArea">
                <div class="text-center h4" style="background-color: #343a4036; color:black;">Invoice</div>
                <div class="row">
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 col-md-5 font-weight-bold">Invoice No:</div>
                            <div class="col-12 col-md-7 font-weight-bold">
                                {{ $data->invoice_no }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">&nbsp;</div>
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 col-md-6 font-weight-bold">Invoice Date:</div>
                            <div class="col-12 col-md-6 font-weight-bold">
                                {{ $data->invoice_date }}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 col-md-5 font-weight-bold">Sold To:</div>
                            <div class="col-12 col-md-7 font-weight-bold">
                                {{ $data->customer->customer_name }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">&nbsp;</div>
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 col-md-6 font-weight-bold">S. Order No:</div>
                            <div class="col-12 col-md-6">
                                {{ $data->s_order_no }}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 col-md-5 font-weight-bold"></div>
                            <div class="col-12 col-md-7">
                                {{ $data->customer->client_address }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">&nbsp;</div>
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 col-md-6 font-weight-bold">Challan No:</div>
                            <div class="col-12 col-md-6">
                                {{ $data->challan_no }}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 col-md-5 font-weight-bold">Consignee :</div>
                            <div class="col-12 col-md-7">
                                {{ $data->cnsignee }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">&nbsp;</div>
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 col-md-6 font-weight-bold">Prepared By:</div>
                            <div class="col-12 col-md-6">
                                <div>
                                    {{ $data->preparedBy ? ($data->preparedBy->employee ? $data->preparedBy->employee->name : '') : '' }}
                                </div>

                            </div>
                            <div class="col-12 col-md-6 font-weight-bold">Approved By:</div>
                            <div class="col-12 col-md-6">
                                <div>
                                    {{ $data->approvedBy ? ($data->approvedBy->employee ? $data->approvedBy->employee->name : '') : '' }}
                                </div>

                            </div>
                            <div class="col-12 col-md-6 font-weight-bold">Project Name:</div>
                            <div class="col-12 col-md-6">
                                <div>
                                    {{ $data->project_name }}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 col-md-5 font-weight-bold">Remarks</div>
                            <div class="col-12 col-md-7">
                                {{ $data->remarks }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">&nbsp;</div>
                    <div class="col-12 col-md-5">
                        <div class="row">
                            <div class="col-12 col-md-6 font-weight-bold">Sales Person:</div>
                            <div class="col-12 col-md-6">
                                {{ $data->employee ? $data->employee->name : '' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 font-weight-bold">Payment Terms:</div>
                            <div class="col-12 col-md-6">
                                {{ $data->payment_terms }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 font-weight-bold">Promised Date:</div>
                            <div class="col-12 col-md-6">
                                {{ $data->promised_date }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 font-weight-bold">Buyer Ref No:</div>
                            <div class="col-12 col-md-6">
                                {{ $data->buyer_ref_no }}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="table-responsive pt-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <th>S/L No/</th>
                            <th>Product Description</th>
                            <th>Quantity</th>
                            <th>Unit Price (BDT)</th>
                            <th>Total Price (BDT)</th>
                        </thead>
                        <tbody>
                            @php
                                $total_quantity = 0;
                                $total_price = 0;
                            @endphp
                            @foreach ($data->items as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <th>{{ $item->product_name ? $item->product_name : ($item->product ? $item->product->name : '') }}
                                    </th>
                                    <td>{{ $item->quantity }} Pcs</td>
                                    <td>{{ $item->unit_price }}</td>
                                    <td>{{ $item->total_price }}</td>
                                </tr>
                                @php
                                    $total_quantity += $item->quantity;
                                    $total_price += $item->total_price;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">In Word Taka:</td>
                                <td>{{ $total_quantity }} Pcs</td>
                                <th>Total Amount:</th>
                                <td>{{ $total_price }}</td>
                            </tr>

                        </tfoot>
                    </table>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4 ml-auto">
                        <div class="d-flex justify-content-between">
                            <div class="font-weight-bold">Net Amount : Tk</div>
                            <div>{{ $data->net_amount }}</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="font-weight-bold">VAT :</div>
                            <div>{{ $data->vat_amount }}</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="font-weight-bold">Service Charge :</div>
                            <div>{{ $data->service_charge }}</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="font-weight-bold">Total :</div>
                            <div>{{ $data->total_amount }}</div>
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="card-body" id="printArea">
                <div class="text-center h4" style="background-color: #343a4036; color:black;">Challan</div>
                <div class="row">
                    <div class="col-12 col-md-8">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th>Challan No</th>
                                <th>{{$data->challan_no}}</th>
                            </tr>
                            <tr>
                                <th>Invoice No</th>
                                <td>{{$data->invoice_no}}</td>
                            </tr>
                            <tr>
                                <th>Buyer No</th>
                                <td>{{$data->buyer_name}}</td>
                            </tr>
                            <tr>
                                <th>Buyer Address</th>
                                <td>{{$data->buyer_address}}</td>
                            </tr>
                            <tr>
                                <th>Delivery Address</th>
                                <td>{{$data->delivery_address}}</td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td>{{$data->remarks}}</td>
                            </tr>
                            <tr>
                                <th>Buyer Ref. No:</th>
                                <td>{{$data->buyer_ref_no}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12 col-md-4">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th>Challan Date</th>
                                <th>{{$data->date}}</th>
                            </tr>
                            <tr>
                                <th>Challan Time</th>
                                <td>{{\Carbon\Carbon::parse($data->time)->format('h:i:s:a')}}</td>
                            </tr>
                            <tr>
                                <th>Buyer Phone</th>
                                <td>{{$data->buyer_phone}}</td>
                            </tr>
                            <tr>
                                <th>Attention</th>
                                <td>{{$data->attention}}</td>
                            </tr>
                            <tr>
                                <th>Payment Terms:</th>
                                <td>{{$data->payment_terms}}</td>
                            </tr>
                            <tr>
                                <th>S. Order No:</th>
                                <td>{{$data->s_order_no}}</td>
                            </tr>
                            <tr>
                                <td colspan="2">{{$data->payment_dead_line}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="table-responsive pt-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <th>S/L No/</th>
                            <th>Product Description</th>
                            <th>Quantity</th>
                        </thead>
                        <tbody>

                            @foreach ($data->items as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <th>{{ $item->product_name ? $item->product_name : ($item->product ? $item->product->name : '') }}
                                    </th>
                                    <td>{{ $item->quantity }} Pcs</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total:</th>
                                <td>{{ $data->total_quantity }} Pcs</td>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
