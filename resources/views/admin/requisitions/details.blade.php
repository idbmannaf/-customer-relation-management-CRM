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
    <link rel="stylesheet" href="{{ asset('back/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> Details Of Requisition {{ $requisition->id }} </div>
        </div>
        @if ($type != 'warranty_claim')
            {{-- <div class="card-body shadow">
            @if ($requisition->visit->offer_quotation)
                @if ($requisition->visit->offer_quotation->customer_approved)
                    <div class="row">
                        <div class="col-12 col-md-8 m-auto bg-success h3" style="padding: 10px 5px;">
                            <a
                                href="{{ route('employee.customerOfferDetails', ['offer' => $requisition->visit->offer_quotation]) }}">Customer
                                Has Been Approved This Quotation</a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-12 col-md-8 m-auto bg-danger h3" style="padding: 10px 5px;">
                            <a
                                href="{{ route('employee.customerOfferDetails', ['offer' => $requisition->visit->offer_quotation]) }}">
                                Customer Does Not Approved This Quotation</a>

                        </div>
                    </div>
                @endif
            @endif
        </div> --}}
        @endif

        <div class="card-body">
            @include('alerts.alerts')
            <form action="{{ route('admin.requisitionUpdate', ['requisition' => $requisition, 'type' => $type]) }}"
                method="post">
                @csrf
                @if ($type == 'spear_parts')

                    @include('admin.requisitions.part.spear_parts_add')
                @elseif ($type == 'product')
                    @include('admin.requisitions.part.product_add')
                @elseif ($type == 'inhouse_product')
                    @include('admin.requisitions.part.inhouse_product_add')
                @elseif ($type == 'warranty_claim')
                    @include('admin.requisitions.part.warranty_claim_add')
                @endif
                <div class="row">
                    <div class="col-13 col-md-3 m-auto">
                        @if ($type != 'warranty_claim')
                            @if ($requisition->status == 'temp')
                                {{-- <input type="submit" class="btn btn-info noPring"> --}}
                                <input type="submit" name="status" value="pending"
                                    class="form-control btn btn-info noPring">
                            @elseif ($requisition->status == 'pending')
                                <input type="submit" name="status" value="Reviewed And Approved"
                                    class="form-control btn btn-warning noPring">
                            @elseif ($requisition->status == 'reviewed')
                                <input type="submit" name="status" value="Approved"
                                    class="form-control btn btn-warning noPring">
                            @endif
                        @else
                            @if ($requisition->status == 'temp')
                                <input type="submit" class="form-control btn btn-info noPring">
                                <input type="submit" name="status" value="pending"
                                    class="form-control btn btn-info noPring">
                            @elseif ($requisition->status == 'pending')
                                <input type="submit" name="status" value="confirmed"
                                    class="form-control btn btn-warning noPring">
                            @endif
                        @endif
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
@push('js')
@endpush
