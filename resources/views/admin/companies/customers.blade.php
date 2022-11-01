@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Customers of {{$company->name}}
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> Customers of {{$company->name}} </div>
                <div>
                    <a href="{{ route('admin.customer.create') }}" class=""><i class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            {{-- <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('admin.importCustomer',$company->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file">
                        <input type="submit" class="btn btn-info" value="Bulk Upload">
                    </form>
                </div>
            </div> --}}
            <div class="showCustomer">
                @include('admin.companies.ajax.companyWiseCustomersAjax')
            </div>

        </div>
    </div>
@endsection



{{-- @push('js')
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
@endpush --}}
