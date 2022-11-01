@extends('employee.layouts.employeeMaster')
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
                                <td>{{$loop->index +1}}</td>
                                <td><a href="{{route('employee.requisitionDetails',['requisition'=>$rq,'type'=>$type,'status'=>$status])}}" class="btn btn-success btn-sm">Details</a></td>
                                <td>
                                    @if ($rq->done_by)
                                    <span class="text-success">Done</span>
                                    @else
                                    <span class="text-warning"> {{$rq->status}}</span>
                                    @endif


                                </td>
                                <td>{{$rq->type}}</td>
                                <td>{{$rq->party_name}}</td>
                                <td>{{$rq->req_product_total_price}}</td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger h3"></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="float-right">
                {{$requisitions->render()}}
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
