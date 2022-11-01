@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Edit Requisition for {{ $type }}
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
    <style>
        span.rq_title {
            font-size: 25px;
            font-weight: 700;
            text-transform: uppercase;
            background-color: gray;
            padding: 10px;
            border-radius: 20px;
        }

        input.custom.form-control {
            border: none;
            border-bottom: 1px solid;
            border-bottom-style: dotted;
            outline: none;
            /* text-align: center; */
        }

        input.custom.form-control:focus-visible {
            border: none !important;
            border-bottom: 1px solid !important;
            border-bottom-style: dotted !important;

            outline: none;
        }

        .item_bottom {
            display: flex;
            align-items: self-end;
            margin-top: 20px;
            font-weight: 500 !important;
        }

        .flex-auto {
            flex: auto;
            width: 120px;
        }

        @media print {
            .noPring {
                display: none;
            }

            .shadow {
                box-shadow: none !important;
            }

            span.rq_title {
                font-size: 25px;
                font-weight: 700;
                text-transform: uppercase;
                background-color: gray;
                padding: 10px;
                border-radius: 20px;
            }

        }
    </style>
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
                                            <a href="{{ route('employee.inventoryMaintainUpdate', ['requisition' => $requisition->id, 'item' => $item, 'type' => $type]) }}"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure? This Product Quantity Decrease from your inventory!!');">Check
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
                                <td>
                                    @if (!$requisition->send_to_receive_by)
                                        <label for="return_{{ $item->id }}"> <input
                                                {{ $item->return_old_product ? 'checked' : '' }} type="checkbox"
                                                onclick="return_management(this,{{ $item->id }})"
                                                id="return_{{ $item->id }}" data-id="{{ $item->id }}">
                                            Return</label>
                                    @endif
                                </td>
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

                    <form action="{{ route('employee.sendToReceived', $requisition) }}" method="POST">
                        @csrf
                        {{-- <div class="form-group">
                            <label for="courier_details">Courier Details</label>
                            <input type="text" name="courier_details" id="courier_details" class="form-control" placeholder="courier details">
                        </div>
                        <div class="form-group">
                            <label for="courier_slip">Courier Slip</label>
                            <input type="file" name="courier_slip" id="courier_slip">

                        </div> --}}
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
            var url = "{{ route('employee.returnMaintainUpdate') }}";
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
