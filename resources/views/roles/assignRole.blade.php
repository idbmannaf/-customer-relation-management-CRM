@extends('admin.layouts.adminMaster')
@push('css')
    <link rel="stylesheet" href="{{ asset('back/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('back/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush
@section('content')
@include('alerts.alerts')
<div class="card">
    <div class="card-header bg-info">
        <div class="card-title">Assign Role</div>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.assignRolePost') }}">
            @csrf
            <div class="input-group mb-3">
                <select id="user" name="user" class="form-control user-select select2-container step2-select select2"
                    data-placeholder="username/E-mail"
                    data-ajax-url="{{ route('admin.selectNewRoleWithoutCustomer') }}" data-ajax-cache="true"
                    data-ajax-dataType="json" data-ajax-delay="200" style="">
                </select>
                {{-- <div class="input-group-append">
            <a title="Add New User" target="_blank"
                href="{{ route('admin.newUserCreate') }}"
                class="btn btn-default"><i class="fa fa-user-plus"></i></a>
        </div> --}}
            </div>
            <div class="input-group mb-3">
                <label for="" class=" col-form-label text-md-right">Role Name</label>
                <div class="ml-2">
                    <select name="role_name" id="" class="form-control select2">
                        <option value="">Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"> {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-info" value="Assign Role">
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('back/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            $('.step2-select').select2({
                theme: 'bootstrap4',
                // minimumInputLength: 1,
                ajax: {
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        // alert(data[0].s);
                        var data = $.map(data, function(obj) {
                            obj.id = obj.id || obj.id;
                            return obj;
                        });
                        var data = $.map(data, function(obj) {
                            obj.text = obj.name + "("+ obj.username +")";
                            return obj;
                        });
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                },
            });


        });
    </script>
@endpush
