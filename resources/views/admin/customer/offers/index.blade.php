@extends('admin.layouts.adminMaster')
@push('title')
    Customer {{ $customer->customer_name }}
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> Offers Customers: {{ $customer->customer_name }}
                    <a href="{{ route('admin.customerOffer.create', $customer) }}" class="btn btn-danger"> New Customer Offer</a>
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
                        @foreach ($customer_offers as $offer)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>
                                    <div class="btn-group">

                                        <button type="button"
                                            class="btn btn-success btn-sm dropdown-toggle dropdown-toggle-split"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.customerOfferEdit', $offer) }}">Edit</a>
                                                <a class="dropdown-item"
                                                href="{{ route('admin.customerOfferDelete', $offer) }}"
                                                onclick="return confirm('Are you sure? You want to delete this Offer?')">Delete</a>

                                            <a class="dropdown-item"
                                                href="{{ route('admin.customerOfferDetails', $offer) }}">Details</a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $offer->customer->customer_name }}</td>
                                <td>{{ $offer->ref }}</td>
                                <td>{{ $offer->date }}</td>
                                <td>{{ $offer->total_price }}</td>
                                <td>{{ $offer->status }}</td>
                                <td>{{ $offer->subject }}</td>
                                <td>
                                    @if ($offer->employee)
                                        {{ $offer->employee->name }} ({{ $offer->employee->employee_id }})
                                    @elseif($offer->addedBy)
                                        {{ $offer->addedBy->name }}
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection



@push('js')
    <script>
        $(document).on('input', '#search', function() {
            var that = $(this);
            var q = that.val();
            var url = that.attr('data-url');
            var finalUrl = url + "?q=" + q;
            $.ajax({
                url: finalUrl,
                method: "GET",
                success: function(res) {
                    $('.showCustomer').html(res)
                }
            })
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    $('.showCustomer').html(res)
                }
            })
        })
    </script>
@endpush
