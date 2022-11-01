@extends('admin.layouts.adminMaster')
@push('title')
    Edit Products
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Stock History Of Product : {{ $product->name }}</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Date</th>
                            <th>Previews Stock</th>
                            <th>Moved Stock</th>
                            <th>Current Stock</th>
                            <th>Purpose</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stock_histories as $history)
                        <tr>
                            <td>{{$loop->index + 1}}</td>
                            <td>{{$history->created_at}}</td>
                            <td>{{$history->previews_stock}}</td>
                            <td>{{$history->moved_stock}}</td>
                            <td>{{$history->current_stock}}</td>
                            <td>{{$history->purpose}}</td>
                            <td>{{$history->note}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-danger text-center">No Stock History Found</td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
