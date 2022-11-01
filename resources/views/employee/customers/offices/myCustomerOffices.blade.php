@extends('Employee.layouts.EmployeeMaster')
@push('title')
    | Employee Dashboard | Customer Company Office Location
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Office Locations of Customer: {{$customer->customer_name}} ({{$customer->customer_code}})
                    <a href="{{ route('employee.myCustomerOfficeAdd',$customer) }}" class="btn btn-danger"><i class="fas fa-plus"></i></a>

            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">

            <div class="d-flex justify-content-end">
                <div class="">
                    <div class="card-body ">
                        <input type="search" class="form-control" id="search"
                            data-url={{ route('employee.myCustomerOfficeSearch',$customer) }} name="q" placeholder="Search">
                    </div>
                </div>
            </div>
            <div class="showOffice">
                @include('employee.customers.offices.ajax.customerOfficelocationAjax')
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
                    $('.showOffice').html(res)
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
                    $('.showOffice').html(res)
                }
            })
        })
    </script>
@endpush
