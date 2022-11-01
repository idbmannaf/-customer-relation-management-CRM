  {{-- Customer Modal Start --}}
  @if ($customer = $visit_plan->customer)
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                      <b>Customer:</b>{{ $visit_plan->customer ? $visit_plan->customer->customer_name : '' }}
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <ul class="list-group">
                      <li class="list-group-item"><b>Name:</b> {{ $customer->customer_name }}</li>
                      <li class="list-group-item"><b>Code:</b>{{ $customer->customer_code }}</li>
                      <li class="list-group-item"><b>Ledger
                              Balance:</b>{{ $customer->ledger_balance }}
                      </li>
                      <li class="list-group-item"><b>Employee Name:</b> {{ $customer->employee_name }}
                      </li>
                      <li class="list-group-item"><b>Company:</b>
                          {{ $customer->company ? $customer->company->name : '' }} </li>
                      <li class="list-group-item"><b>Permanent Address:</b> {{ $customer->client_address }},
                          {{ $customer->area }}, {{ $customer->thana }},
                          {{ $customer->district }},
                          {{ $customer->division }} </li>
                      <li class="list-group-item"><b>Mobile:</b> {{ $customer->mobile }} </li>
                      <li class="list-group-item"><b>Email:</b> {{ $customer->email }} </li>
                  </ul>
              </div>

          </div>
      </div>
  </div>
@endif
{{-- Customer Modal End --}}
