<div class="modal fade" id="vp-{{ $visit_plan->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Call/Task/Complain Of This Visit Plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
                $call = $visit_plan->call;
            @endphp
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item"><b>Call ID: </b>{{ $call->id }}</li>
                    <li class="list-group-item"><b>Date Time: </b>{{ $call->date_time }}</li>
                    <li class="list-group-item"><b>Customer :
                        </b>{{ $call->customer ? $call->customer->customer_name : '' }}
                        ({{ $call->customer ? $call->customer->customer_code : '' }})</li>
                    <li class="list-group-item"><b>Customer Address/Location: </b>
                        {{ $call->customer_office ? $call->customer_office->title : $call->customer_address }} <br></li>
                    <li class="list-group-item"> <b>{{ ucfirst($call->type) }} Address: </b>
                        {{ $call->service_address }}</li>
                    @if ($call->employee)
                        <li class="list-group-item"><b>Employee: </b>{{ $call->employee->name }}
                            ({{ $call->employee->employee_id }})</li>
                    @endif
                    @if ($call->customer && $call->customer->user && $call->customer->user->username)
                        <li class="list-group-item"> <b>Email: </b>
                            <a class="btn btn-success btn-xs" href="tel:{{ $call->customer->user->username }}"><i
                                    class="fas fa-envelope"></i>{{ $call->customer->user->username }}</a>
                        </li>
                    @endif
                    @if ($call->customer && $call->customer->mobile)
                        <li class="list-group-item"><b>Call: </b>
                            <a class="btn btn-warning btn-xs" href="tel:{{ $call->customer->mobile }}"><i
                                    class="fas fa-phone-volume"></i>{{ $call->customer->mobile }}</a>
                        </li>
                    @endif
                    <li class="list-group-item"><b>Purpose Of Visit: </b>{{ $call->purpose_of_visit }}</li>
                    <li class="list-group-item"><b>Admin Note: </b>{{ $call->admin_note }}</li>

                </ul>
            </div>

        </div>
    </div>
</div>
