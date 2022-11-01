@extends('admin.layouts.adminMaster')
@push('title')
    Ready To recevie Unused Product From {{ $type }}
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            Ready To recevie Unused Product From {{ $type }}

        </div>

        @include('alerts.alerts')
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Action</th>
                        <th>Assigned To</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        {{-- <th>Repair Status</th>
                        <th>Recharge Status</th> --}}
                        <th>Unit Price</th>
                        <th>Final Price</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($receivedProductFromUnused as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>
                                @if (!$item->status)
                                    <div class="btn-group btn-sm">
                                        <a href="{{ route('admin.sendReceiveUnusedProductToTeamMember', ['unused' => $item, 'status' => 'repair']) }}"
                                            class="btn btn-success btn-xs">Repair</a>
                                        <a href="{{ route('admin.sendReceiveUnusedProductToTeamMember', ['unused' => $item, 'status' => 'recharge']) }}"
                                            class="btn btn-warning btn-xs">Recharge</a>
                                        <a href="{{ route('admin.sendReceiveUnusedProductToTeamMember', ['unused' => $item, 'status' => 'bad']) }}"
                                            class="btn btn-danger btn-xs">Bad</a>
                                    </div>
                                    {{-- @elseif ($item->received)
                                    <span class="badge badge-success">Received</span> --}}
                                @endif


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
                            {{-- <td>
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
                            </td> --}}
                            <td>{{ $item->unit_price }}</td>
                            <td>{{ $item->total_price }}</td>

                            <td>
                                @if (!$item->custom_entry)
                                <a href="{{ route('admin.customerVisitview', ['visit_plan' => $item->visit->visit_plan_id, 'visit' => $item->visit_id]) }}"
                                    class="btn btn-sm btn-warning">Log</a>
                                <a href="{{ route('admin.editRequisition', ['requisition' => $item->requisition_id, 'type' => $type, 'visit' => $item->visit_id]) }}"
                                    class="btn btn-sm btn-primary">Requisition</a>
                                    @else
                                    {{$item->details}}
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $receivedProductFromUnused->render() }}
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
