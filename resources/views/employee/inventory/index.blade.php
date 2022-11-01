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
            {{ $type }} Requisition For Inventory
        </div>

        @include('alerts.alerts')
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        <th>Action</th>
                        {{-- <th>Status</th> --}}
                        <th>Party_name</th>
                        <th>Total Product</th>
                        <th>Stocked Out</th>
                        {{-- <th>Current Not Out Stock</th> --}}
                        <th>Req Product Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requisitions as $rq)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $rq->created_at }}</td>
                            <td><a href="{{ route('employee.inventoryMaintainDetails', ['requisition' => $rq, 'type' => $type]) }}"
                                    class="btn btn-success btn-sm">Details</a></td>
                                    {{-- <td>
                                        @if ($rq->send_to_receive_by)
                                        Delivered
                                        @else
                                       {{ $rq->status}}
                                        @endif
                                    </td> --}}
                            <td>{{ $rq->party_name }}</td>
                            <td>{{ $rq->requisitionProducts->count() }}</td>
                            <td>{{ $rq->stockedOutRequisitionProductsCount() }}</td>
                            {{-- <td>{{$rq->requisitionProducts->count() - $rq->stockedOutRequisitionProductsCount()}}</td> --}}
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

        {{ $requisitions->render() }}


    </div>
@endsection


@push('js')
@endpush
