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
            Requisition for {{ $type }}
            <a href="{{ route('employee.requisition', ['visit' => $visit, 'type' => $type]) }}"
                class="btn btn-sm btn-warning">Back</a>
            @if ($requisition->status == 'reviewed' ||
                $requisition->status == 'approved' ||
                $requisition->status == 'confirmed')
                <a href="#" onclick="return window.print();" class="btn btn-xs btn-primary"><i class="fa fa-print"></i>
                </a>
            @endif


        </div>
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
        <form
            action="{{ route('employee.updateRequisition', ['visit' => $visit, 'type' => $type, 'requisition' => $requisition->id]) }}"
            method="POST">
            @csrf


            @include('alerts.alerts')
            @if ($type == 'spear_parts')
                @include('employee.requisition.part.spear_parts_add')
            @elseif ($type == 'product')
                @include('employee.requisition.part.product_add')
            @elseif ($type == 'inhouse_product')
                @include('employee.requisition.part.inhouse_product_add')
            @elseif ($type == 'warranty_claim')
                @include('employee.requisition.part.warranty_claim_add')
            @endif

            {{-- {{ dump(auth()->user()->employee->team_admin) }}
            {{ dump($type) }} --}}
            <div class="row">
                <div class="col-13 col-md-3 m-auto">
                    @if ($type != 'warranty_claim')
                        @if ($requisition->status == 'temp')
                            {{-- <input type="submit" class="btn btn-info noPring"> --}}
                            <input type="submit" name="status" value="pending" class="form-control btn btn-info noPring">
                        @elseif ($requisition->status == 'pending' &&
                            auth()->user()->employee->company->logo_and_req_permission &&
                            auth()->user()->employee->team_admin)
                            <input type="submit" name="status" value="reviewed"
                                class="form-control btn btn-warning noPring">
                        @endif
                    @else
                        @if ($requisition->status == 'temp')
                            <input type="submit" class="form-control btn btn-info noPring">
                            <input type="submit" name="status" value="pending" class="form-control btn btn-info noPring">
                        @elseif ($requisition->status == 'pending' &&
                            auth()->user()->employee->company->logo_and_req_permission &&
                            auth()->user()->employee->team_admin)
                            <input type="submit" name="status" value="confirmed"
                                class="form-control btn btn-warning noPring">
                        @endif
                    @endif
                </div>
            </div>


            {{-- @if ($requisition->status == 'temp')
                <input type="submit" class="btn btn-info noPring">
                <input type="submit" name="status" value="pending" class="btn btn-info noPring">
            @elseif ($requisition->status == 'pending')
                <input type="submit" name="status" value="confirmed" class="btn btn-info noPring">
            @endif --}}
        </form>
    </div>
@endsection


@push('js')
    <!-- summernote css/js -->
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>

        $(document).on('input', '#service_charge', function() {

            var final_price =Number( $("#final_price").val());
            var service_charge = Number( $(this).val());
            var grand_total = final_price + service_charge;
            $('.grand_total').text(grand_total)
        })
    </script>
@endpush
