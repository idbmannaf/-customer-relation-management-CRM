@extends('admin.layouts.adminMaster')
@push('title')
    Products Creation
@endpush
@push('css')
    <style>
        select#backUpType {
            background: none;
            border: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('back/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> {{ ucfirst($status) }} Requisition of {{ ucwords(str_replace('_', ' ', $type)) }}
            </div>
        </div>
        <div class="card-body">
            @if ($type =='warranty_claim')

                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-nowrap">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Requisition Type</th>
                                <th>Party_name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requisitions as $rq)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a href="{{ route('admin.requisitionDetails', ['requisition' => $rq, 'type' => $type, 'status' => $status]) }}"
                                            class="btn btn-success btn-sm">Details</a></td>
                                    <td>{{ $rq->status }}</td>
                                    <td>{{ $rq->type }}</td>
                                    <td>{{ $rq->party_name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger h3"></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-nowrap">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Action</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Requisition Type</th>
                                <th>Party_name</th>
                                <th>Req Product Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requisitions as $rq)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a href="{{ route('admin.requisitionDetails', ['requisition' => $rq, 'type' => $type, 'status' => $status]) }}"
                                            class="btn btn-success btn-sm">Details</a></td>
                                    <td>{{ $rq->status }}</td>
                                    <td>{{ $rq->created_at }}</td>
                                    <td>{{ $rq->type }}</td>
                                    <td>{{ $rq->party_name }}</td>
                                    <td>{{ $rq->req_product_total_price }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger h3"></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
            {{-- @if ($type == 'spear_parts')
                @include('admin.requisitions.part.spear_parts')
            @elseif ($type == 'product')
                @include('admin.requisitions.part.products')
            @elseif ($type == 'inhouse_product')
                @include('admin.requisitions.part.inhouse_products')
            @endif --}}

            <div class="float-right">
                {{ $requisitions->render() }}
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
