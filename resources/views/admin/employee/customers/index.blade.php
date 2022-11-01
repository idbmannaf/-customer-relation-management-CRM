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
                <div> Customers of Employee : {{$employee->name}} ({{$employee->employee_id}})</div>
                @can('customer-add')
                    <div>
                        <a href="{{ route('admin.employeeCustomersAdd',$employee) }}" class=""><i class="fas fa-plus"></i></a>
                    </div>
                @endcan


            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">

            @can('customer-search')
                <div class="d-flex justify-content-end">
                    <div class="">
                        <div class="card-body ">
                            <input type="search" class="form-control" id="search"
                                data-url={{ route('admin.employeeCustomersSearch',$employee) }} name="q" placeholder="Search">
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
