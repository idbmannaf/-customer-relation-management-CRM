@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Create Users
@endpush

@push('css')
@endpush

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <div class="card-title">All Users</div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3 mb-2">
                    <input type="text" name="q" id="search" class="form-control"
                        placeholder="Search Username or Name" data-url="{{ route('admin.user.search') }}">
                </div>
            </div>
            <div class="showUser">
                @include('admin.users.ajax.userList')
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
                    $('.showUser').html(res);
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
                        $('.showUser').html(res);
                    }
                })
            }, 400);
        })
    </script>
@endpush
