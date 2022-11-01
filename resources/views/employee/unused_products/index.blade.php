@extends('employee.layouts.employeeMaster')
@push('title')
    Unused Product From {{ $type }}
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            Unused Product From {{ $type }}

        </div>

        @include('alerts.alerts')
        <div class="table responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>Customer</th>
                        <th>Return</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Final Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unusedProducts as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $item->created_at }}</td>

                            <td>
                                @if (!$item->send)
                                    <a href="{{ route('employee.unUsedProductSendToStoreHead', ['item' => $item]) }}"
                                        class="btn btn-warning btn-xs"
                                        onclick="return confirm('Are you sure? You want send this produt in Store?');">Send</a>
                                @endif
                                @if ($item->send)
                                <span class="badge badge-success">Sent</span>
                                @endif

                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('employee.customerVisitview', ['visit_plan' => $item->visit->visit_plan_id, 'visit' => $item->visit_id]) }}"
                                        class="btn btn-xs btn-warning">Log</a>
                                    <a href="{{ route('employee.editRequisition', ['requisition' => $item->requisition_id, 'type' => $type, 'visit' => $item->visit_id]) }}"
                                        class="btn btn-xs btn-primary">Requisition</a>

                                    @if ($item->visit && $item->visit->offer_id)
                                        <a href="{{ route('employee.customerOfferDetails', ['offer' => $item->visit->offer_id]) }}"
                                        class="btn btn-xs btn-success">Offer/Quatation</a>
                                    @endif

                                </div>
                            </td>
                            <td>
                                {{$item->customer ? $item->customer->customer_name : ''}}
                            </td>
                            <td>
                                @if ($item->return_old_product)
                                    <i class="text-success fas fa-check"></i>
                                @endif
                            </td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit_price }}</td>
                            <td>{{ $item->total_price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $unusedProducts->render() }}
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
