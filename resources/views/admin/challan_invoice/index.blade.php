@extends('admin.layouts.adminMaster')
@push('title')
    {{ $type }} List
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            {{ $type }} List

        </div>

        @include('alerts.alerts')
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Action</th>
                        <th>Status</th>
                        <th>date</th>
                        @if ($type == 'invoice')
                            <th>Net Amount</th>
                            <th>Service Charge</th>
                            <th>Total Price</th>
                        @endif
                        <th>Challan No</th>
                        <th>Invoice No</th>
                        <th>Customer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td><a href="{{ route('admin.chalanAndInvoiceDetails', ['id' => $data->id, 'type' => $type]) }}"
                                    class="fas fa-view btn btn-success btn-sm">Details</a></td>

                            <td>
                                @if ($type == 'challan')
                                    @if ($data->visit && $data->visit->visit_plan && $data->visit->visit_plan->service_type == 'sales')
                                        @if ($data->product_received)
                                            <span class="badge badge-success">Product Received</span>
                                        @else
                                            <a href="{{ route('admin.chalanProductReceived', ['challan' => $data]) }}"
                                                class="fas fa-view btn btn-warning btn-sm">Product Received</a>
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($type == 'challan')
                                    {{ $data->date }}
                                @else
                                    {{ $data->invoice_date }}
                                @endif

                            </td>

                            @if ($type == 'invoice')
                                <td>{{ $data->net_amount }}</td>
                                <td>{{ $data->service_charge }}</td>
                                <td>
                                    {{ $data->total_amount }}
                                </td>
                            @endif
                            <td>{{ $data->challan_no }}</td>
                            <td>{{ $data->invoice_no }}</td>
                            <td>{{ $data->customer ? $data->customer->customer_name : '' }}</td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $datas->render() }}
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
