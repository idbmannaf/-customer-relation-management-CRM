@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Terams
@endpush

@push('css')
    <link href="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css') }}"
        rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ asset('https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css') }}">
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Roles Of Team: {{$team->name}}
                {{-- <a href="{{ route('admin.team.create') }}" class="btn btn-danger"><i
                        class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')

        <div class="row p-1">
            <div class="col-12 col-lg-6">
                <form action="{{ route('admin.addTeamRole', ['team' => $team, 'type' => 'admin']) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Add Admin Role</label>
                        <select id="admin" name="user" class="form-control select2-container step2-select select2"
                            data-placeholder="Name, Email" data-ajax-url="{{ route('admin.selectNewRole') }}"
                            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200" style="width: 100%;">
                            <option>{{ old('user') }}</option>
                        </select>
                        <div class="form-group pt-2">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>Role Name</th>
                            <th>User</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($team->teamAdmin() as $team_admin)
                            <tr>
                                <td>{{ $team_admin->pivot->role_name }}</td>
                                <td>{{ $team_admin->name }}</td>
                                <td>
                                    <a href="{{ route('admin.roleDelete', ['role' => $team_admin->pivot->id, 'team' => $team]) }}"
                                        class="text-danger deleteRole"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        <tr></tr>
                    </table>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <form action="{{ route('admin.addTeamRole', ['team' => $team, 'type' => 'member']) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Add Member Role</label>
                        <select id="member" name="user" class="form-control select2-container step2-select select2"
                            data-placeholder="Name, Email" data-ajax-url="{{ route('admin.selectNewRole') }}"
                            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200" style="width: 100%;">
                            <option>{{ old('user') }}</option>
                        </select>
                        <div class="form-group pt-2">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </div>
                </form>
                <div class="showTeamMember">
                    @include('admin.teams.ajax.memberAjax')
                </div>

            </div>
        </div>

    </div>
@endsection



@push('js')
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                minimumInputLength: 1,
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
                            obj.id = obj.id || obj.email;
                            return obj;
                        });
                        var data = $.map(data, function(obj) {
                            obj.text = obj.text || obj.email;
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
    <script>
        $(document).on('click', '.deleteRole', function(e) {
            e.preventDefault();
            var that = $(this);
            var url = that.attr('href');
            if (confirm('Are You Sure? You want to delete this Role?')) {

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(res) {
                        if (res.success) {
                            that.closest('tr').remove();
                        }
                    }
                })
            }
        })
    </script>


    <script>
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    $('.showTeamMember').html(res)
                }
            })
        })
    </script>
@endpush
