@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Customer Company Office Location
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Customer Office Locations
                @can('office-location-add')
                <a href="{{ route('admin.customerOffice.create') }}" class="btn btn-danger">New Office Location</a>
                @endcan
            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            {{-- <div class="row pb-2">
                <div class="col-12 col-md-3">
                    <fieldset>
                        <legend>Bulk Upload <a href="{{ asset('img/customer.png') }}" class="badge badge-danger" title="Follow The instruction "><i class="fas fa-info"></i></a>
                        </legend>
                        <form action="{{ route('admin.bulkUpload') }}" enctype="multipart/form-data" method="post">
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

            </div> --}}
            <div class="d-flex justify-content-end">
                <div class="">
                    <div class="card-body ">
                        <input type="search" class="form-control" id="search"
                            data-url={{ route('admin.customerCompanyOfficeSearch') }} name="q" placeholder="Search">
                    </div>
                </div>
            </div>
            <div class="showOffice">
                @include('admin.officeLocation.ajax.customerOfficelocationAjax')
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
