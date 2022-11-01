<div class="card shadow">
    <div class="card-header">
        <h4>Invoice ({{ $invoice->invoice_no }})</h4>
    </div>
    <div class="card-body" id="printArea">
        {{-- <div class="text-center h4" style="background-color: #343a4036; color:black;">Invoice</div> --}}
        <div class="row">
            <div class="col-12 col-md-5">
                <div class="row">
                    <div class="col-12 col-md-5 font-weight-bold">Invoice No:</div>
                    <div class="col-12 col-md-7 font-weight-bold">
                        {{ $invoice->invoice_no }}
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">&nbsp;</div>
            <div class="col-12 col-md-5">
                <div class="row">
                    <div class="col-12 col-md-6 font-weight-bold">Invoice Date:</div>
                    <div class="col-12 col-md-6 font-weight-bold">
                        {{ $invoice->invoice_date }}
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12 col-md-5">
                <div class="row">
                    <div class="col-12 col-md-5 font-weight-bold">Sold To:</div>
                    <div class="col-12 col-md-7 font-weight-bold">
                        {{ $invoice->customer->customer_name }}
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">&nbsp;</div>
            <div class="col-12 col-md-5">
                <div class="row">
                    <div class="col-12 col-md-6 font-weight-bold">S. Order No:</div>
                    <div class="col-12 col-md-6">
                        {{ $invoice->s_order_no }}
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12 col-md-5">
                <div class="row">
                    <div class="col-12 col-md-5 font-weight-bold"></div>
                    <div class="col-12 col-md-7">
                        {{ $invoice->customer->client_address }}
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">&nbsp;</div>
            <div class="col-12 col-md-5">
                <div class="row">
                    <div class="col-12 col-md-6 font-weight-bold">Challan No:</div>
                    <div class="col-12 col-md-6">
                        {{ $invoice->challan_no }}
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12 col-md-5">
                <div class="row">
                    <div class="col-12 col-md-5 font-weight-bold">Consignee :</div>
                    <div class="col-12 col-md-7">
                        {{ $invoice->cnsignee }}
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">&nbsp;</div>
            <div class="col-12 col-md-5">
                <div class="row">
                    <div class="col-12 col-md-6 font-weight-bold">Prepared By:</div>
                    <div class="col-12 col-md-6">
                        <div>
                            {{ $invoice->preparedBy ? ($invoice->preparedBy->employee ? $invoice->preparedBy->employee->name : '') : '' }}
                        </div>

                    </div>
                    <div class="col-12 col-md-6 font-weight-bold">Approved By:</div>
                    <div class="col-12 col-md-6">
                        <div>
                            {{ $invoice->approvedBy ? ($invoice->approvedBy->employee ? $invoice->approvedBy->employee->name : '') : '' }}
                        </div>

                    </div>
                    <div class="col-12 col-md-6 font-weight-bold">Project Name:</div>
                    <div class="col-12 col-md-6">
                        <div>
                            {{ $invoice->project_name }}
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
                        {{ $invoice->remarks }}
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">&nbsp;</div>
            <div class="col-12 col-md-5">
                <div class="row">
                    <div class="col-12 col-md-6 font-weight-bold">Sales Person:</div>
                    <div class="col-12 col-md-6">
                        {{ $invoice->customer ? ($invoice->customer->employee ? $invoice->customer->employee->name : '') : '' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 font-weight-bold">Payment Terms:</div>
                    <div class="col-12 col-md-6">
                        {{ $invoice->payment_terms }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 font-weight-bold">Promised Date:</div>
                    <div class="col-12 col-md-6">
                        {{ $invoice->promised_date }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 font-weight-bold">Buyer Ref No:</div>
                    <div class="col-12 col-md-6">
                        {{ $invoice->buyer_ref_no }}
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
                    @foreach ($invoice->items as $item)
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
                    <div>{{ $invoice->net_amount }}</div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">VAT :</div>
                    <div>{{ $invoice->vat_amount }}</div>
                </div>
                @if ($invoice->service_charge)
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">Service Charge :</div>
                    <div>{{ $invoice->service_charge }}</div>
                </div>
                @endif

                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">Total :</div>
                    <div>{{ $invoice->total_amount }}</div>
                </div>

            </div>
        </div>
    </div>
</div>
