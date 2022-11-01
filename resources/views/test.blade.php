<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1">
    <title>Hotel Reservation</title>
    <style>
        tr,
        td {
            border: 1px solid black;

        }
    </style>
</head>

<body
    style="background-color:#F5F6F8;font-family:-apple-system, BlinkMacSystemFont, 'segoe ui', roboto, oxygen, ubuntu, cantarell, 'fira sans', 'droid sans', 'helvetica neue', Arial, sans-serif;box-sizing:border-box;font-size:16px;">
    <div style="background-color:#fff;margin:30px;box-sizing:border-box;font-size:16px;">
        <h1
            style="padding:30px;box-sizing:border-box;font-size:24px;color:#ffffff;background-color:#cb5f51;margin:0; text-align: center;">
            Offer Quotation</h1>

        <h2 style="padding:20px 40px;margin:0;color:#394453;box-sizing:border-box;"> <a href="{{route('employee.customerOfferDetails',$offer)}}" target="_blank"> View In Web</a></h2>
        <div style="box-sizing:border-box;padding:0 40px 20px;">
            <b>Ref:</b> {{ $offer->ref }} <br>
            <b>Date:</b> {{ $offer->date }}<br>
            <b style="padding-top: 5px;">To:</b> {!! $offer->to !!}
            <b>Subject: {{ $offer->subject }}</b>
            <p>
                {!! $offer->body !!}
            </p>
            <p>
                {!! $offer->signature !!}
            </p>



            <div class="financial_proposal">
                <b style="text-decoration: underline">Financial Proposal:</b> <br> <br>
                <table class="table table-sm table-bordered" style="width: 100%;border-collapse: collapse;">
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
                                    <table class="" style="border: none; width: 100%">
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
                            </th>
                        </tr>

                        <tr>
                            <th style="border: none; text-align:right;" colspan="4">Service Charge:</th>
                            <th colspan="1">{{ $offer->service_charge }}</th>
                        </tr>

                        <tr>
                            <th style="border: none; text-align:right;" colspan="4">Sub Total:</th>
                            <th colspan="1" class="subtotal">{{ $offer->service_charge + $offer->total_price() }}
                            </th>
                        </tr>

                    </tfoot>
                </table>
            </div>
            <div class="terms-and-condition">
                <b style="text-decoration: underline;">Terms & Conditions:</b> <br><br>
                {!! $offer->terms_and_condition !!}
            </div>

            <div style="padding-top:10px; border-top:2px solid black; font-size:14px;">
                Corporate Head Office at Concord Tower, Suit No. 1401, 113 Kazi Nazrul Islam Avenue, Dhaka, Bangladesh.
                Registered Office: Motaleb Tower, 8/2 Paribag, 1st Floor, Flat-2C, Dhaka-1205.
                IP Phone: <a style="color: green" href="tel:09675117807">09675117807</a>|PABX: 02-41031890 | e-mail: <a
                    style="color: green" href="mailto:info@orient.com.bd">info@orient.com.bd</a> | web: <a style="color: green"
                    href="www.orient.com.bd">www.orient.com.bd</a>

            </div>
        </div>
    </div>
</body>

</html>
