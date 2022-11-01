@extends('employee.layouts.employeeMaster')
@push('title')
    {{ ucfirst($status) }} Products
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            {{ ucfirst($status) }} Products

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
                        <th>Total Quantity</th>
                        <th>Total Reuse</th>
                        <th>Total Bad</th>
                        <th>Available Quantity</th>
                        {{-- <th>Status</th>
                        <th>Repair Status</th>
                        <th>Recharge Status</th> --}}
                        <th>Unit Price</th>
                        <th>Final Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($receiveUnusedProducts as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>
                                @if ($item->due() > 0)
                                    @if ($item->status == 'recharge')
                                        @if (!$item->recharge_status)
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#reuse_{{ $item->id }}" data-whatever="@mdo">Reuse</button>
                                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#not_possible_{{ $item->id }}" data-whatever="@mdo">Not Possible </button>
                                            {{-- <a href="{{ route('employee.receiveUnusedProductStatusUpdate', ['item' => $item, 'status' => 'recharge_status', 'update' => 'reuse']) }}"
                                                class="btn btn-success btn-xs"
                                                onclick="return confirm('Are you sure? You to Reuse?');">Reuse</a>
                                            <a href="{{ route('employee.receiveUnusedProductStatusUpdate', ['item' => $item, 'status' => 'recharge_status', 'update' => 'bad']) }}"
                                                class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure? This product is bad?');">Not
                                                Possible</a> --}}
                                        @elseif ($item->recharge_status == 'bad')
                                            <span class="badge badge-danger">bad</span>
                                        @elseif ($item->recharge_status == 'use')
                                            <span class="badge badge-success">Reused</span>
                                        @endif
                                    @endif

                                    @if ($item->status == 'repair')
                                        @if (!$item->repair_status)
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#reuse_{{ $item->id }}" data-whatever="@mdo">Reuse</button>
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#not_possible_{{ $item->id }}" data-whatever="@mdo">Not Possible </button>
                                        @elseif ($item->repair_status == 'bad')
                                            <span class="badge badge-danger">bad</span>
                                        @elseif ($item->repair_status == 'use')
                                            <span class="badge badge-success">Reused</span>
                                        @endif
                                    @endif
                                @endif


                            </td>
                            <td>{{ $item->assignedTo ? $item->assignedTo->name : '' }}</td>

                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->total_reuse }}</td>
                            <td>{{ $item->total_bad }}</td>
                            <td>{{ $item->due() }}</td>
                            {{-- <td>
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
                            </td> --}}
                            <td>{{ $item->unit_price }}</td>
                            <td>{{ $item->total_price }}</td>
                        </tr>
                        {{-- Modal --}}
                        <div class="modal fade" id="reuse_{{ $item->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Reuse Product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        @if ($item->status == 'recharge')
                                            <form
                                                action="{{ route('employee.receiveUnusedProductStatusUpdate', ['item' => $item, 'status' => 'recharge_status', 'update' => 'reuse']) }}"
                                                method="POST">
                                            @elseif ($item->status == 'repair')
                                                <form
                                                    action="{{ route('employee.receiveUnusedProductStatusUpdate', ['item' => $item, 'status' => 'repair_status', 'update' => 'reuse']) }}"
                                                    method="POST">
                                        @endif
                                        @csrf

                                        <div class="form-group">
                                            <label for="">Total Quantity : {{ $item->quantity }}</label> <br>
                                            <label for="">Total Reused : {{ $item->total_reused }}</label> <br>
                                            <label for="">Total Bad : {{ $item->total_bad }}</label> <br>
                                            <label for="">Current Quantity : {{ $item->due() }}</label>
                                            <input type="hidden" name="due_quantity" class="due_quantity"
                                                value="{{ $item->due() }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity" class="col-form-label">Reuse Quantity:</label>
                                            <input type="number" class="form-control quantity" min="1" name="quantity"
                                                id="quantity">
                                            <span class="text-danger qtyError"></span>
                                        </div>
                                        <button type="submit" class="btn btn-primary add">Submit</button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="not_possible_{{ $item->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Not Possible Product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        @if ($item->status == 'recharge')
                                            <form
                                                action="{{ route('employee.receiveUnusedProductStatusUpdate', ['item' => $item, 'status' => 'recharge_status', 'update' => 'bad']) }}"
                                                method="POST">
                                            @elseif ($item->status == 'repair')
                                                <form
                                                    action="{{ route('employee.receiveUnusedProductStatusUpdate', ['item' => $item, 'status' => 'repair_status', 'update' => 'bad']) }}"
                                                    method="POST">
                                        @endif
                                        @csrf

                                        <div class="form-group">
                                            <label for="">Total Quantity : {{ $item->quantity }}</label> <br>
                                            <label for="">Total Reused : {{ $item->total_reused }}</label> <br>
                                            <label for="">Total Bad : {{ $item->total_bad }}</label> <br>
                                            <label for="">Current Quantity : {{ $item->due() }}</label>
                                            <input type="hidden" name="due_quantity" class="due_quantity"
                                                value="{{ $item->due() }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity" class="col-form-label">Bad Quantity:</label>
                                            <input type="number" class="form-control quantity"  min="1" name="quantity"
                                                id="quantity">
                                            <span class="text-danger qtyError"></span>
                                        </div>
                                        <button type="submit" class="btn btn-primary add">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $receiveUnusedProducts->render() }}
    </div>
@endsection
@push('js')
    <script>
        $(document).on('input', '.quantity', function(e) {
            var that = $(this);
            var quantity = Number(that.val());
            var due_qty = Number(that.closest('.modal-body').find('.due_quantity').val());
            if (quantity > due_qty) {
                that.closest('.modal-body').find('.qtyError').text(
                    `Current Quantity is ${due_qty} . Quantity must be less then equal to ${due_qty}`);
                that.closest('.modal ').find('.add').attr('disabled', true);

            } else {
                that.closest('.modal-body').find('.qtyError').text('')
                that.closest('.modal ').find('.add').attr('disabled', false);
            }
        })
    </script>
@endpush
