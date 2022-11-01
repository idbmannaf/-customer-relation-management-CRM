@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Customers
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> Customers
                    @can('customer-add')
                        <a href="{{ route('admin.customer.create') }}" class="btn btn-danger">New Customer</a>
                    @endcan
                </div>
                <button class="btn btn-warning">Total:customer: {{ $total_customer }}</button>


            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">

            {{-- @can('customer-bulk-upload')
                <div class="row pb-2">
                    <div class="col-12 col-md-3">
                        <fieldset>
                            <legend>Bulk Upload <a href="{{ asset('img/customer.png') }}" class="badge badge-danger" title="Follow The instruction "><i class="fas fa-info"></i></a>
                            </legend>
                            <form action="{{ route('admin.importCustomer') }}" enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="file">
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Upload" class="btn btn-info">
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>
            @endcan --}}

            @can('customer-search')
                <div class="d-flex justify-content-end">
                    <div class="">
                        <div class="card-body ">
                            <input type="search" class="form-control" id="search"
                                data-url={{ route('admin.customerSearch') }} name="q" placeholder="Search">
                        </div>
                    </div>
                </div>
            @endcan


            <div class="showCustomer">
                @include('admin.customer.ajax.customersAjax')
            </div>
        </div>
    </div>
@endsection



@push('js')
    <script>
        $(function() {
            //delay function start
            var delay = (function() {
                var timer = 0;
                return function(callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();

            $(document).on('input', '#search', function() {
                var that = $(this);
                var q = that.val();
                var url = that.attr('data-url');
                var finalUrl = url + "?q=" + q;
                delay(function() {
                    $.ajax({
                        url: finalUrl,
                        method: "GET",
                        success: function(res) {
                            $('.showCustomer').html(res)
                        }
                    })
                }, 300);
            });
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
