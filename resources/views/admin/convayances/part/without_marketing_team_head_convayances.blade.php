<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div> Claim Details For Convayances Bill</div>
            <div>
                <b>Payemnt Status: </b>
                @if ($convayance->paid)
                    <span class="badge badge-success">Paid</span>
                @else
                    <span class="badge badge-danger">Unpaid</span>
                @endif
            </div>
            <div><b>Status: </b>
                @if ($convayance->status == 'pending')
                    <span class="badge badge-warning">Pending</span>
                @elseif ($convayance->status == 'rejected')
                    <span class="badge badge-danger">Rejected</span>
                @elseif ($convayance->status == 'approved')
                    <span class="badge badge-success">Approved</span>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-8 m-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Amount</th>
                                        <th>Travel Mode</th>
                                        <th>Movement Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($convayance->items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->amount }}</td>
                                            <td>{{ $item->travel_mode }}</td>
                                            <td>{{ $item->movement_details }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between">
            <div class="total_amount"> <b>Total Amount: </b>{{ $convayance->total_amount }}</div>
        </div>
    </div>



</div>
