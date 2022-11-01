@extends('admin.layouts.adminMaster')
@push('title')
    Inventory Details of Requisition: {{ $requisition->id }}
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            Inventory Details of Requisition: {{ $requisition->id }}
        </div>

        @include('alerts.alerts')
        <div class="table responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        @if ($requisition->visit->received_by || $requisition->visit->done_by)
                        @else
                            <th>Action</th>
                        @endif
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Final Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requisition->requisitionProducts as $item)
                        @if (!$item->stock_out)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>
                                    @if (!$requisition->send_to_receive_by)
                                        @if (!$item->ok_by)
                                            <a href="{{ route('admin.inventoryMaintainUpdate', ['requisition' => $requisition->id, 'item' => $item, 'type' => $type]) }}"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure? This Product Quantity Decrease from the inventory!!');">Check
                                                Availability</a>
                                        @endif
                                        <label for="return_{{ $item->id }}"> <input
                                                {{ $item->return_old_product ? 'checked' : '' }} type="checkbox"
                                                onclick="return_management(this,{{ $item->id }})"
                                                id="return_{{ $item->id }}" data-id="{{ $item->id }}">
                                            Return</label>
                                    @else

                                    @endif


                                </td>

                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit_price }}</td>
                                <td>{{ $item->total_price }}</td>
                            </tr>
                        @else
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td></td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit_price }}</td>
                                <td>{{ $item->total_price }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (!$requisition->send_to_receive_by)
            <div class="row">
                <div class="col-12 col-md-5 m-auto">
                    <form action="{{ route('admin.sendToReceived', $requisition) }}" method="POST">
                        @csrf
                        <input type="hidden" name="purpose" value="send_to_receive_by">
                        <input type="submit" value="Delivered" class="form-control btn btn-success">
                    </form>
                </div>
            </div>
        @endif

    </div>
@endsection
@push('js')
    <script>
        function return_management(e, req_product_id) {
            var that = $(e);
            console.log(that.attr('data-id'));
            var id = that.attr('data-id');
            var url = "{{ route('admin.returnMaintainUpdate') }}";
            var finalUrl = url + "?item=" + id;
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    if (res) {}
                }
            })
        }
    </script>
@endpush
