<div class="modal fade" id="invoice_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Invoice Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
               $invoice =  $visit_plan->invoice;
            @endphp
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><b>Invoice ID: </b>{{$invoice->id}}</li>
                    {{-- <li class="list-group-item"><b>Date Time: </b>{{$call->date_time}}</li>
                    <li class="list-group-item"><b>Customer : </b>{{$call->customer? $call->customer->customer_name : "" }} ({{$call->customer? $call->customer->customer_code : "" }})</li>
                    <li class="list-group-item"><b>Customer Address/Location: </b> {{ $call->customer_office ? $call->customer_office->title : $call->customer_address }} <br></li>
                    @if ($call->employee)
                    <li class="list-group-item"><b>Employee: </b>{{$call->employee->name  }} ({{$call->employee->employee_id}})</li>
                    @endif
                    <li class="list-group-item"><b>Purpose Of Visit: </b>{{$call->purpose_of_visit}}</li>
                    <li class="list-group-item"><b>Admin Note: </b>{{$call->admin_note}}</li> --}}

                  </ul>
            </div>

        </div>
    </div>
</div>
