@extends('admin.layouts.adminMaster')
@push('title')
    Admihn Dashboard |Requisition for {{ $type }}
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
    </style>
@endpush
@section('content')
@include('alerts.alerts')
    <form
        action="{{ route('admin.updateRequisition', ['visit' => $visit, 'type' => $type, 'requisition' => $requisition->id]) }}"
        method="POST">
        @csrf
        <div class="card shadow">
            <div class="card-header bg-info">
                Requisition for {{ $type }}
                <a href="{{ route('admin.requisition', ['visit' => $visit, 'type' => $type]) }}"
                    class="btn btn-sm btn-warning">Back</a>

            </div>

            @if ($type == 'spear_parts')
                @include('admin.requisitions.part.spear_parts_add')
            @elseif ($type == 'product')
                @include('admin.requisitions.part.product_add')
            @elseif ($type == 'inhouse_product')
                @include('admin.requisitions.part.inhouse_product_add')
                @elseif ($type == 'warranty_claim')
                @include('admin.requisitions.part.warranty_claim_add')
            @endif
        </div>
        <input type="submit" class="btn btn-info">
    </form>
@endsection


@push('js')
    <!-- summernote css/js -->
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@endpush
