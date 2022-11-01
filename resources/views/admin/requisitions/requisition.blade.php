@extends('admin.layouts.adminMaster')
@push('title')
    Admin Dashboard |Requisition for {{ $type }}
@endpush

@push('css')
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
    </style>
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            Requisitions Of {{ $type }}
            @if ($visit->status != 'completed')
                @can('requisition-add')
                    <a href="{{ route('admin.addRequisition', ['type' => $type, 'visit' => $visit]) }}"
                        class="btn btn-sm btn-danger">New Requisition</a>
                @endcan
            @endif
            {{-- <a href="{{ route('employee.customerVisits', ['visit_plan' => $visit->visit_plan]) }}"
                class="btn btn-sm btn-warning">Back</a> --}}
        </div>
        <div class="card-body">
            @include('alerts.alerts')
            @if ($type == 'warranty_claim')
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
                                    <td><a href="{{ route('admin.editRequisition', ['visit' => $visit, 'type' => $type, 'requisition' => $rq->id]) }}"
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
                                    <td><a href="{{ route('admin.editRequisition', ['visit' => $visit, 'type' => $type, 'requisition' => $rq->id]) }}"
                                            class="btn btn-success btn-sm">Details</a></td>
                                    <td>{{ $rq->status }}</td>
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
        </div>

    </div>
@endsection


@push('js')
    <!-- summernote css/js -->
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@endpush
