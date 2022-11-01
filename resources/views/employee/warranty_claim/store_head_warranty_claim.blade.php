@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Requisition for Warranty Claim
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
    <div class="card shadow">
        <div class="card-header bg-info">
            Requisitions Of  Warranty Claim
        </div>


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
                            <td><a href="{{ route('employee.warrantyClaimDetails', ['requisition' => $rq]) }}"
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

        {{-- @if ($type == 'spear_parts')
            @include('employee.requisition.part.spear_parts')
        @elseif ($type == 'product')
            @include('employee.requisition.part.products')
        @if ($type == 'inhouse_product')
            @include('employee.requisition.part.inhouse_products')

        @endif --}}


    </div>
@endsection


@push('js')
    <!-- summernote css/js -->
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@endpush
