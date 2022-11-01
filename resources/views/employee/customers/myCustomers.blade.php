@extends('employee.layouts.employeeMaster')
@push('title')
    | Emplyee Dashboard | My Customers
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> My Customers
                    <a href="{{ route('employee.myCustomers.create') }}" class="btn btn-danger"> New Customer</a>
                </div>

            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <div class="">
                    <div class="card-body ">
                        <input type="search" class="form-control" id="search"
                            data-url={{ route('employee.myCustomerSearch') }} name="q" placeholder="Search">
                    </div>
                </div>
            </div>
            <div class="showCustomer">
                @include('employee.customers.ajax.mycustomerAjax')
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
