@extends('admin.layouts.adminMaster')
@push('title')
   Transaction History
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
            Transaction History
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
        <div class="card-body" id="printArea">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>Collection By</th>
                            <th>Payment Method</th>
                            <th>Previous Amount</th>
                            <th>Moved Amount</th>
                            <th>Current Amount</th>
                            <th>Purpose</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction_histories as $th)
                        <tr>
                            <td>{{$th->id}}</td>
                            <td>{{$th->created_at}}</td>
                            <td>{{$th->invoice_id}}</td>
                            <td>{{$th->collectedBy ? $th->collectedBy->name : ''}}</td>
                            <td>{{$th->payment_method}}</td>
                            <td>{{$th->prev_due}}</td>
                            <td>{{$th->moved_amount}}</td>
                            <td>{{$th->current_due}}</td>
                            <td>{{$th->purpose}}</td>
                            <td>{{$th->note}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{$transaction_histories->render()}}
        </div>
    </div>
@endsection
