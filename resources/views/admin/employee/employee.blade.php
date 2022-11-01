@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Employees
@endpush


@section('content')
    <div class="card shadow">

        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> Employees
                    @can('employee-add')
                        <a href="{{ route('admin.employee.create') }}" class="btn btn-danger">New Employee</a>
                    @endcan
                </div>
                <button class="btn btn-warning">Total Employee: {{ $total_employee }}</button>

            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            @can('employee-search')
                <div class="d-flex justify-content-end pb-2">
                    <div class="">
                        <div class="d-flex justify-content-end">
                            <input type="text" name="q" id="search" class="form-control"
                                placeholder="Search via Emplyee Id or Name" data-url="{{ route('admin.employee.search') }}">
                        </div>
                    </div>
                </div>
            @endcan


            <div class="showEmployee">
                @include('admin.employee.ajax.employeeList')
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        var delay = (function() {
            var timer = 0;
            return function(callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    $('.showEmployee').html(res);
                }
            })
        })

        $(document).on('input', '#search', function(e) {
            var q = $(this).val();
            var url = $(this).attr('data-url');
            var finalUrl = url + "?q=" + q;
            delay(function() {
                $.ajax({
                    url: finalUrl,
                    method: "GET",
                    success: function(res) {
                        $('.showEmployee').html(res);
                    }
                })
            }, 400);
        })
    </script>
@endpush
