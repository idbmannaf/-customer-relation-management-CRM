@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Edit Requisition for {{ $type }}
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            Ready to Recived Product of Requisition: {{ $requisition->id }}
            <a href="" class="btn btn-sm btn-warning">Visit</a>
            <a href="{{ route('employee.customerVisitview', ['visit_plan' => $requisition->visit->visit_plan_id, 'visit' => $requisition->visit_id]) }}"
                class="btn btn-sm btn-secondary">Log</a>
            <a href="{{ route('employee.editRequisition', ['requisition' => $requisition, 'type' => $type, 'visit' => $requisition->visit_id]) }}"
                class="btn btn-sm btn-primary">Requisition</a>
        </div>

        @include('alerts.alerts')
        <div class="table responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Action</th>
                        @if ($requisition->received_by && !$requisition->done_by)
                            <th>Return</th>
                        @else
                            <th>Need To Return?</th>
                        @endif
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Final Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requ_products as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>

                            <td>
                                @if ($requisition->received_by && !$requisition->done_by)
                                    <label for="checked_{{ $item->id }}"> <input type="checkbox" name="check"
                                            class="useUnuse_returned" id="checked_{{ $item->id }}"
                                            data-url="{{ route('employee.requisitionProductStatusUpdate', ['item' => $item, 'status' => 'useunuse']) }}"
                                            {{ $item->used ? 'checked' : '' }}> Used</label>
                                @endif
                            </td>


                            @if ($requisition->received_by && !$requisition->done_by)
                                <td>
                                    @if ($item->return_old_product)
                                        <label for="checked_return_{{ $item->id }}"> <input type="checkbox" name="check"
                                                class="useUnuse_returned" id="checked_return_{{ $item->id }}"
                                                data-url="{{ route('employee.requisitionProductStatusUpdate', ['item' => $item]) }}"
                                                {{ $item->returned ? 'checked' : '' }}>Return</label>
                                    @endif

                                </td>
                            @else
                                <td>
                                    @if ($item->return_old_product)
                                        <i class="text-success fas fa-check"></i>
                                    @else
                                        <i class="text-danger fas fa-times"></i>
                                    @endif
                                </td>
                            @endif
                            {{-- <td><a href="{{route('employee.inventoryMaintainUpdate',['requisition'=>$requisition->id,'item'=>$item,'type'=>$type])}}" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure? This Product Quantity Decrease from your inventory!!');">OK</a>
                                </td> --}}
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit_price }}</td>
                            <td>{{ $item->total_price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-12 col-md-3 m-auto text-center">
                @if ($requisition->received_by && !$requisition->done_by)
                    <form
                        action="{{ route('employee.readyToReceiveProductDetailsUpdate', ['requisition' => $requisition, 'status' => 'done']) }}">
                        <input type="submit" value="Done" class="btn btn-success form-control">
                    </form>
                @elseif ($requisition->done_by)
                    <div class="card shadow">
                        <h3>Requisition: Done</h3>
                    </div>
                @else
                    <form
                        action="{{ route('employee.readyToReceiveProductDetailsUpdate', ['requisition' => $requisition, 'status' => 'received']) }}">
                        <input type="submit" value="Received" class="btn btn-success form-control">
                    </form>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).on('click', '.useUnuse_returned', function() {
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
        $(document).on('click', '.returned', function() {
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
