@extends('admin.layouts.adminMaster')
@push('title')
    Old Stock {{ $status }} Producs
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info noPring">
            Old Stock {{ $status }} Producs

        </div>

        @include('alerts.alerts')
        <div class="table responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Product Name</th>
                        <th>Total Quantity</th>
                        <th>Total Reuse</th>
                        <th>Total Bad</th>
                        <th>Available Quantity</th>
                        <th>Status</th>
                        <th>Unit Price</th>
                        <th>Final Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $item)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>

                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->total_reuse }}</td>
                            <td>{{ $item->total_bad }}</td>
                            <td>
                                @if ($item->due() > 0)
                                    <span class="badge badge-danger">{{ $item->due() }}</span>
                                    @else
                                    {{$item->due()}}
                                @endif
                            </td>
                            <td>
                                @if ($item->status == 'repair')
                                    <div class="badge badge-warning">Repair</div>
                                @elseif ($item->status == 'recharge')
                                    <div class="badge badge-success">Recharge</div>
                                @elseif ($item->status == 'bad')
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
        {{ $products->render() }}
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
