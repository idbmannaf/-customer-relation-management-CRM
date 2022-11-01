@extends('customer.layouts.customerMaster')
@push('title')
    Customer Dashboard | Attendance Report
@endpush

@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> {{ucfirst($type)}} Offers/Quotations</div>

            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Action</th>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Ref</th>
                            <th>Date</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Subject</th>
                            {{-- <th>Offerd By</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offers as $offer)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>
                                    @if ($offer->customer_approved)
                                    <a class="" href="{{ route('customer.offerDetails', $offer) }}"><i
                                        class="fas fa-eye"></i></a>
                                    @else
                                    <a class="btn btn-success btn-sm" href="{{ route('customer.offerDetails', $offer) }}">Details For Accept</a>
                                    @endif

                                </td>
                                <td>{{$offer->created_at}}</td>
                                <td>{{ $offer->employee ? $offer->employee->name : '' }}</td>
                                <td>{{ $offer->ref }}</td>
                                <td>{{ $offer->date }}</td>
                                <td>{{ $offer->subtotal_price }}</td>
                                <td>
                                    @if ($offer->customer_approved)
                                        <span class="badge badge-success">Accepted</span>
                                    @else
                                        <span class="badge badge-warning">Not Apprved Yet</span>
                                    @endif
                                </td>
                                <td>{{ $offer->subject }}</td>
                                {{-- <td>
                                @if ($offer->employee)
                                {{$offer->employee->user->name}} ({{$offer->employee->employee_id}})
                                @endif
                            </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $offers->render() }}
        </div>
    </div>
@endsection
