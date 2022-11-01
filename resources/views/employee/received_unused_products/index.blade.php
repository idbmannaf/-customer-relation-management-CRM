@extends('employee.layouts.employeeMaster')
@push('title')
    Ready To recevie Unused Product From {{ $type }}
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            Ready To recevie Unused Product From {{ $type }}

        </div>

        @include('alerts.alerts')
        <div class="table responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Customer</th>
                        <th>Assigned To</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Repair Status</th>
                        <th>Recharge Status</th>
                        <th>Unit Price</th>
                        <th>Final Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($receiveUnusedProducts as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>

                            @if (!$item->received)
                                <a href="{{ route('employee.sendReceiveUnusedProductToTeamMember', ['unused' => $item, 'status' => 'received']) }}"class="btn btn-danger btn-xs">Receive</a>
                                @else
                                <span class="badge badge-success">Received</span>
                            @endif
                            </td>
                            <td>

                                <a href="{{ route('employee.customerVisitview', ['visit_plan' => $item->visit->visit_plan_id, 'visit' => $item->visit_id]) }}"
                                    class="btn btn-sm btn-warning">Log</a>
                                <a href="{{ route('employee.editRequisition', ['requisition' => $item->requisition_id, 'type' => $type, 'visit' => $item->visit_id]) }}"
                                    class="btn btn-sm btn-primary">Requisition</a>
                            </td>
                            <td>
                                {{$item->customer ? $item->customer->customer_name : ''}}
                            </td>
                            <td>{{$item->assignedTo ? $item->assignedTo->name : ''}}</td>

                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                @if ($item->status == 'repair')
                                    <div class="badge badge-warning">Repair</div>
                                @elseif ($item->status == 'recharge')
                                    <div class="badge badge-success">Recharge</div>
                                @elseif ($item->status == 'bad')
                                    <div class="badge badge-danger">Bad</div>
                                @endif
                            </td>
                            <td>
                                @if ($item->repair_status == 'use')
                                    <div class="badge badge-success">Reuse</div>
                                @elseif ($item->repair_status == 'bad')
                                    <div class="badge badge-danger">Bad</div>
                                @endif
                            </td>
                            <td>
                                @if ($item->recharge_status == 'use')
                                    <div class="badge badge-success">Reuse</div>
                                @elseif ($item->recharge_status == 'bad')
                                    <div class="badge badge-danger">Bad</div>
                                @endif
                            </td>
                            <td>{{ $item->unit_price }}</td>
                            <td>{{ $item->total_price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $receiveUnusedProducts->render() }}
    </div>
@endsection

@push('js')
    <script>
        $(document).on('click', '.checkedItem', function() {
            var that = $(this);
            var url = that.attr('data-url');
            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    // console.log(res);
                }

            })
        })
    </script>
@endpush
