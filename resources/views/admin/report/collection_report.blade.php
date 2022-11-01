@extends('admin.layouts.adminMaster')
@push('title')
    Admin Dashboard |Collection History
@endpush
@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            Collection Report
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="c_type">Collection Type</label>
                            <select name="c_type" id="c_type" class="form-control">
                                <option value="">Select Collection Type</option>
                                <option {{ request()->c_type == 'paid' ? 'selected' : '' }} value="paid">Paid</option>
                                <option {{ request()->c_type == 'plus' ? 'selected' : '' }} value="plus">unpaid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="from">
                                From
                            </label>
                            <input type="date" name="from" class="form-control" value="{{ request()->from }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="to">
                                To
                            </label>
                            <input type="date" name="to" class="form-control" value="{{ request()->to }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="">&nbsp;</label>
                        <input type="submit" class="form-control btn btn-info">
                    </div>
                </div>
            </form>
            @if (isset($collections))
            <div class="table-responsive">
            <table class="table table-sm table-brodered text-nowrap">
                <thead>
                    <tr>
                        <th>#SL</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Invoice</th>
                        <th>Visit/ Investigation</th>
                        <th>Collected By</th>
                        <th>Amount</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_amount=0;
                    @endphp
                    @foreach ($collections as $collection)
                    <tr>
                        <td>{{$loop->index + 1}}</td>
                        <td>{{$collection->created_at}}</td>
                        <td>{{$collection->customer ? $collection->customer->customer_name : ''}}</td>
                        <td>{{$collection->invoice_id}}</td>
                        <td>{{$collection->visit_id }}</td>
                        <td>{{$collection->collectedBy ? $collection->collectedBy->name : '' }}</td>
                        <td>{{$collection->moved_amount}}</td>
                        <td>{{$collection->note}}</td>
                    </tr>
                    @php
                        $total_amount +=$collection->moved_amount;
                    @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right">Total Amount</td>
                        <td colspan="2">{{$total_amount}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
            @endif
        </div>
    </div>
@endsection
