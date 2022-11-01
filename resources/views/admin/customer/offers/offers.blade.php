@extends('admin.layouts.adminMaster')
@push('title')
    Offers Dashboard | Customer Offer
@endpush

@push('css')
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> {{ ucfirst($type) }} Customer Offers
                </div>
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Action</th>
                            <th>Customer</th>
                            <th>Ref</th>
                            <th>Date</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Subject</th>
                            <th>Offerd By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offers as $offer)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>

                                    <a class=""
                                        href="{{ route('admin.offerDetails', ['customer' => $offer->customer, 'offer' => $offer]) }}"><i
                                            class="fas fa-eye"></i></a>

                                    @if (($offer->status == 'approved' && !$offer->customer_approved) || $offer->status == 'pending' )
                                        <a class=""
                                            href="{{ route('admin.customerOfferEdit', ['customer' => $offer->customer, 'offer' => $offer]) }}"><i
                                                class="fas fa-edit"></i></a>
                                    @endif
                                </td>
                                <td>{{ $offer->customer->customer_name }}</td>
                                <td>{{ $offer->ref }}</td>
                                <td>{{ $offer->date }}</td>
                                <td>{{ $offer->total_price }}</td>
                                <td>{{ $offer->status }}</td>
                                <td>{{ $offer->subject }}</td>
                                <td>
                                    @if ($offer->employee)
                                        {{ $offer->employee->user->name }} ({{ $offer->employee->employee_id }})
                                        {{-- @else
                                    {{$offer->addedBy}} --}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $offers->render() }}
        </div>
    </div>
@endsection
