@extends('admin.layouts.adminMaster')
@push('title')
    Customer {{ $customer->customer_name }}
@endpush

@push('css')
    <style>
        @media print {

            .productItemTable tr,
            .productItemTable td {
                border: none !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div>Offer Details of Customers: {{ $customer->customer_name }} ({{ $customer->customer_code }})
                    <a href="{{ route('employee.customerOffer', $customer) }}" class="btn btn-warning"> Back</a>
                </div>
                <a href="" onclick="return printDiv('printArea');" class="btn btn-danger ">Print</a>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
        }
    </script>
    @include('alerts.alerts')
    <form action="{{ route('admin.customerOfferStatusUpdate', $offer) }}" method="POST">
        @csrf
        <div class="card px-4" id="printArea">
            <div class="d-flex justify-content-end">
                <img style="width: 200px;" src="{{ asset('img/orient.png') }}" alt="" srcset="">
            </div>
            <div class="d-flex justify-content-between pt-3">
                <div><b>Ref: </b>{{ $offer->ref }}</div>
                <div><b>Date: </b>{{ $offer->date }}</div>
            </div>
            <div class="to">
                To:

                {!! $offer->to !!}
            </div>
            <div class="subject">
                <b>
                    Subject:

                    {{ $offer->subject }}
                </b>
            </div>
            <div class="pt-3 body">
                {!! $offer->body !!}
            </div>
            <div class="pt-3 signature">
                ---------------------------
                {!! $offer->signature !!}
            </div>

            <div class="financial_proposal">
                <b style="text-decoration: underline">Financial Proposal:</b> <br> <br>
                <table class="table table-sm table-bordered">
                    <thead style="background-color: gray">
                        <tr>
                            <td>SL</td>
                            <th>Item Description</th>
                            <th>QTY</th>
                            <th>Unit Price (BDT)</th>
                            <th>Total Price (BDT)</th>
                        </tr>
                    </thead>
                    <tbody class="showItem">
                        @foreach ($offer->items as $item)
                            <tr>
                                <td>
                                    @if ($loop->index < 10)
                                        {{ '0' . $loop->index + 1 }}
                                    @else
                                        {{ $loop->index + 1 }}
                                    @endif
                                </td>
                                <td>
                                    <table class="table productItemTable" style="border: none">
                                        <tr style="border: none; !important">
                                            <td style="border: none; !important">Name:</td>
                                            <td style="border: none; !important">{{ $item->product_name }}</td>
                                        </tr>
                                        <tr style="border: none; !important">
                                            <td style="border: none; !important">VAH:</td>
                                            <td style="border: none; !important">{{ $item->product_capacity }}</td>
                                        </tr>
                                        <tr style="border: none; !important">
                                            <td style="border: none; !important">Brand:</td>
                                            <td style="border: none; !important"> {{ $item->product_brand }}</td>
                                        </tr>
                                        <tr style="border: none; !important">
                                            <td style="border: none; !important">Type:</td>
                                            <td style="border: none; !important">{{ $item->product_type }}</td>
                                        </tr>
                                        <tr style="border: none; !important">
                                            <td style="border: none; !important">Origin:</td>
                                            <td style="border: none; !important">{{ $item->product_origin }}</td>
                                        </tr>
                                        <tr style="border: none; !important">
                                            <td style="border: none; !important">Made In:</td>
                                            <td style="border: none; !important">{{ $item->product_made_in }}</td>
                                        </tr>
                                        <tr style="border: none; !important">
                                            <td style="border: none; !important">Warranty:</td>
                                            <td style="border: none; !important">{{ $item->product_warranty }}</td>
                                        </tr>

                                    </table>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit_price }}
                                </td>
                                <td class="total_price">{{ $item->total_price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="border: none; text-align:right;" colspan="4">Total Price:</th>
                            <th colspan="1">{{ $offer->total_price() }}
                                <input type="hidden" id="total_price" value="{{ $offer->total_price() }}">
                            </th>
                        </tr>

                        <tr>
                            <th style="border: none; text-align:right;" colspan="4">Service Charge:</th>
                            <th colspan="1">{{ $offer->service_charge }}</th>
                        </tr>

                        <tr>
                            <th style="border: none; text-align:right;" colspan="4">Sub Total:</th>
                            <th colspan="1" class="subtotal">{{ $offer->service_charge + $offer->total_price() }}</th>
                        </tr>

                    </tfoot>
                </table>
            </div>
            <div class="terms-and-condition">
                <b style="text-decoration: underline;">Terms & Conditions:</b> <br><br>
                {!! $offer->terms_and_condition !!}
            </div>

            <div class="bar" style="border-bottom: 2px solid black;">&nbsp;</div>
            <div class="footer pt-1 pb-1" style="font-size: 11px;">
                Corporate Head Office at Concord Tower, Suit No. 1401, 113 Kazi Nazrul Islam Avenue, Dhaka, Bangladesh.
                Registered Office: Motaleb Tower, 8/2 Paribag, 1st Floor, Flat-2C, Dhaka-1205.
                IP Phone: <a style="color: green" href="tel:09675117807">09675117807</a>|PABX: 02-41031890 | e-mail: <a
                    style="color: green" href="mailto:info@orient.com.bd">info@orient.com.bd</a> | web: <a
                    style="color: green" href="www.orient.com.bd">www.orient.com.bd</a>

            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 m-auto">
                @if ($offer->status == 'pending')
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <input type="submit" value="Approve For Show In Customer Dashboard"
                        class="form-control btn btn-success">
                @elseif ($offer->status == 'approved' && !$offer->customer_approved)
                    <input type="hidden" name="status" value="customer_approved">
                    <input type="submit" value="Customer Approved" class="form-control btn btn-success">
                @endif

            </div>
        </div>
    </form>
    {{-- <button id="cmd">Generate PDF</button> --}}
@endsection

{{-- @push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <script>
        var doc = new jsPDF();
        // var specialElementHandlers = {
        //     '#editor': function(element, renderer) {
        //         return true;
        //     }
        // };

        doc.fromHTML(document.getElementById("printArea"), function() {
            console.log("Callback");
            doc.save('test.pdf');
        })
        // $('#cmd').click(function() {
        //     doc.fromHTML($('#printArea').html(), 15, 15, {
        //         'width': 170,
        //         'elementHandlers': specialElementHandlers
        //     });
        //     doc.save('sample-file.pdf');
        // });



        // This code is collected but useful, click below to jsfiddle link.
    </script>
@endpush --}}
