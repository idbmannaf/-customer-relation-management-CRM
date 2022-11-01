<div class="card">
    <div class="card-header bg-gray">Collection Part</div>
    <div class="card-body">
        @if ($invoice)
            <input type="hidden" name="invoice" value="{{ $invoice->id }}">
            <input type="hidden" id="total_invoice_amount"
                value="@if ($visit_plan->payment_maturity_date)  {{$visit_plan->payment_collection_amount}} @else {{ $invoice->due > 0 ? $invoice->due : $invoice->total_amount }} @endif">
            @include('employee.account.part.invoice_card')
            @else
            <input type="hidden" id="total_invoice_amount"
            value="{{ $visit_plan->customer->ledger_balance }}">
        @endif

        <div class="row">
            <div class="col-12 col-md-6 pb-2">
                <label for="payment_method">Payment Method </label>
                <select name="payment_method" id="payment_method" class="form-control">
                    <option value="">Select Payment Method</option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>

            <div class="col-12 col-md-6 payment_collection_date">

            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-md-6" id="show_payment_maturity_date">

                    </div>
                    <div class="col-12 col-md-6" id="show_check_number">

                    </div>
                </div>
            </div>

            <div class="col-12">
                <label for="collection_details" class="form-label">Collection Details</label>
                <textarea name="collection_details" id="collection_details" cols="30" rows="2"
                    class="form-control @error('collection_details') is-invalid @enderror"> </textarea>
            </div>
            @if ($invoice)
                <div class="col-12">
                    <label for="collection_amount">Collection Amount
                        @if ($visit_plan->payment_maturity_date)  {{$visit_plan->payment_collection_amount}} @else {{ $invoice->due > 0 ? $invoice->due : $invoice->total_amount }} @endif </label>
                    <input type="number"
                        class="form-control collection_amount @error('collection_amount') is-invalid @enderror "
                        name="collection_amount" value="{{ old('collection_amount') }}">
                </div>
                <div class="col-12 showNextDate">
                </div>
            @else
                <div class="col-12">
                    <label for="collection_amount">Collection Amount {{$visit_plan->customer->ledger_balance}}</label>
                    <input type="number" class="form-control collection_amount @error('collection_amount') is-invalid @enderror "
                        name="collection_amount" value="{{ old('collection_amount') }}">
                </div>
            @endif

        </div>


    </div>
</div>

@push('js')
    @if ($invoice)
        <script>
            $('.collection_amount').on('input', function(e) {
                var invoice_amount = Number($('#total_invoice_amount').val());
                var collection_amount = $(this).val();
                if ((collection_amount > invoice_amount || collection_amount >= invoice_amount)) {
                    $(".showNextDate").html('');

                } else {
                    var html = `
                <label for="next_collection_date">Next Collection Date <b id="due">Due:{{ $invoice->total_amount }}</b></label>
                <input type="date"
                    class="form-control  @error('next_collection_date') is-invalid @enderror "
                    name="next_collection_date" value="{{ old('next_collection_date') }}">`;
                    $(".showNextDate").html(html);
                    var due = invoice_amount - collection_amount;
                    $("#due").html(due);
                }
                var purpose_of_visit = collection_amount +
                    ` TK  Paid from Invoice Id: {{ $invoice->id }} @if ($visit_plan->payment_maturity_date)  from Payment Maturity Date ({{$visit_plan->payment_maturity_date}})  And Collection Amount ({{$visit_plan->payment_collection_amount}})  @endif and Total Amount {{ $invoice->total_amount }}`;
                $("#purpose_of_visit").text(purpose_of_visit);
            })

        </script>
        @else
        <script>
            $('.collection_amount').on('input', function(e) {
                var invoice_amount = Number($('#total_invoice_amount').val());
                var collection_amount = $(this).val();
                if (collection_amount < 1) {
                  alert(1)

                }
                var purpose_of_visit = collection_amount +
                    ` TK Paid from Customer {{$visit_plan->customer_id}} `;
                $("#purpose_of_visit").text(purpose_of_visit);
            })

        </script>
    @endif
    <script>
        $(document).on('change','#payment_method',function(){
                var that = $(this);
                var payment_method = that.val();
                var html =` <label for="payment_maturity_date">Payment Maturity Date @if ($invoice)
                        ({{ $visit_plan->payment_maturity_date }})
                    @endif
                </label>
                <input type="date" class="form-control @error('payment_maturity_date') is-invalid @enderror " required
                    name="payment_maturity_date" value="{{ old('payment_maturity_date') }}">`;

                    var check_number =` <label for="check_number">Check Number
                </label>
                <input type="text" class="form-control @error('check_number') is-invalid @enderror " required
                    name="check_number" value="{{ old('check_number') }}">`;
                if (payment_method == 'cheque') {
                    $("#show_payment_maturity_date").html(html);
                    $("#show_check_number").html(check_number);
                }else{
                    $("#show_payment_maturity_date").html('');
                    $("#show_check_number").html('');
                }
                if (payment_method == 'cash') {
                    var html = `<label for="payment_collection_date">Payment Collection Date
                </label>
                <input type="date" class="form-control @error('payment_collection_date') is-invalid @enderror " required
                    name="payment_collection_date" value="{{ old('payment_collection_date') }}">`;
                    $(".payment_collection_date").html(html);
                }else{
                    $(".payment_collection_date").html('');

                }
            })
    </script>
@endpush
