@extends('employee.layouts.employeeMaster')
@push('title')
    Transection Details
@endpush
@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            @if (request()->invoice)
                {{-- Transection History Of Invoice IDL: ({{ request()->invoice }}) --}}
            @else
                Transection History of Customer
            @endif
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
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <td>Transection Id</td>
                            <td>Date</td>
                            <td>Customer</td>
                            <td>Previous Due</td>
                            <td>Moved Amount</td>
                            <td>Current Due</td>
                            <td>Payment Method</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $history)
                        <tr>
                            <td>{{$history->id}}</td>
                            <td>{{$history->created_at}}</td>
                            <td>{{$history->customer ? $history->customer->customer_name : ''}}</td>
                            <td>{{$history->prev_due}}</td>
                            <td>{{$history->moved_amount}}</td>
                            <td>{{$history->current_due}}</td>
                            <td>{{$history->payment_method}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            {{$histories->render()}}
         </div>

    </div>
@endsection
