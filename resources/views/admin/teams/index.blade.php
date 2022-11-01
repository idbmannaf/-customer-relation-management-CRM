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
            <div class="card-title">Teams
                {{-- <a href="{{ route('admin.team.create') }}" class="btn btn-danger"><i
                        class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card">
            <div class="card-header">
                <form action="{{ route('admin.team.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Team Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invald @enderror()"
                            placeholder="Team Name here...">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Team Description</label>
                        <input type="text" name="description" id="description"
                            class="form-control @error('description') is-invald @enderror()"
                            placeholder="Description here...">
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="company">Company</label>
                        <select name="company" id="company" class="form-control @error('company') is-invald @enderror()">
                            <option value="">Select Company</option>
                            @foreach ($companies as $company)
                            <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="form-group">
                        <label for="">Role</label>
                        <select id="user" name="user" class="form-control select2-container step2-select select2"
                            data-placeholder="Name, Email" data-ajax-url="{{ route('admin.selectNewRole') }}"
                            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200" style="width: 100%;">
                            <option>{{ old('user') }}</option>
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <label for="active"> <input type="checkbox" name="active" id="active"> Active</label>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-info">
                    </div>
                </form>
            </div>

        </div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                    <th>Id</th>
                    <th>Action</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Company</th>
                    <th>Total Role</th>
                    <th>Active</th>
                </thead>
                <tbody>
                    @foreach ($teams as $team)
                        <tr>
                            <td>{{ $team->id }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button"
                                        id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Action
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="" class="btn btn-primary" data-toggle="modal"
                                            data-target="#edit{{ $team->id }}">Edit</a>
                                        <a class="dropdown-item" href="{{ route('admin.teamRoles', $team) }}">Roles</a>
                                        <form action="{{ route('admin.team.destroy', ['team' => $team]) }}"
                                            method="POST">
                                            @method("DELETE")
                                            @csrf
                                            <input type="submit" style="background-color:transparent; border:none;"
                                                value="Delete"
                                                onclick="return confirm('Are you sure? you want to delete this team?');">
                                        </form>

                                    </div>
                                </div>
                            </td>
                            <td>{{ $team->name }}</td>
                            <td>{{ $team->description }}</td>
                            <td>{{ $team->company ? $team->company->name : '' }}</td>
                            <td>{{ count($team->team_roles) }}</td>
                            <td>
                                @if ($team->active)
                                    <span class="text-success">Yes</span>
                                @else
                                    <span class="text-danger">No</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Edit team Modal --}}
                        <div class="modal fade" id="edit{{ $team->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit: {{ $team->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.team.update', ['team' => $team]) }}" method="POST">
                                            @method('PATCH')
                                            @csrf
                                            <div class="form-group">
                                                <label for="name">Copmpany Name</label>
                                                <input type="text" name="name" id="name"
                                                    class="form-control @error('name') is-invald @enderror()"
                                                    placeholder="Team Name here..." value="{{ $team->name }}">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Copmpany Description</label>
                                                <input type="text" name="description" id="description"
                                                    class="form-control @error('description') is-invald @enderror()"
                                                    placeholder="description here..." value="{{ $team->description }}">
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="company">Company</label>
                                                <select name="company" id="company" class="form-control @error('company') is-invald @enderror()" >
                                                    <option value="">Select Company</option>
                                                    @foreach ($companies as $company)
                                                    <option {{$company->id == $team->company_id ? 'selected' : ''}} value="{{$company->id}}">{{$company->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="active"> <input type="checkbox"
                                                        {{ $team->active ? 'checked' : '' }} name="active" id="active">
                                                    Active</label>
                                            </div>

                                            <div class="form-group">

                                                <input type="submit" class="btn btn-info">
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- Edit team Modal --}}
                    @endforeach
                </tbody>
            </table>

            {{ $teams->render() }}
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
@endpush
