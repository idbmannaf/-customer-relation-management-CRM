@extends('employee.layouts.employeeMaster')
@push('title')
 Emplyee Dashboard | Other Customers
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div>Other Customers
                    <a href="{{route('employee.myCustomers.create')}}" class="btn btn-danger"> <i class="fas fa-plus"></i></a>
                </div>

            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <div class="">
                    <div class="card-body ">
                        <input type="search" class="form-control" id="search"
                            data-url={{ route('employee.othersCustomerSearch') }} name="q" placeholder="Search">
                    </div>
                </div>
            </div>
            <div class="showCustomer">
                @include('employee.customers.ajax.myOtherCustomerAjax')
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
