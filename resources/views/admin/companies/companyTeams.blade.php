@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Teams of {{ $company->name }}
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> Teams of {{ $company->name }} </div>
                {{-- <div>
                    <a href="{{ route('admin.customer.create') }}" class=""><i class="fas fa-plus"></i></a>
                </div> --}}
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="card shadow">
                <div class="card-body">
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
                        <input type="hidden" name="company" value="{{$company->id}}">
                        <div class="form-group">
                            <label for="active"> <input type="checkbox" name="active" id="active"> Active</label>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </form>
                </div>
            </div>
            <div class="showCustomer shadow">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-nowrap">
                        <thead>
                            <th>Id</th>
                            <th>Team Name</th>
                            <th>Description</th>
                            <th>Active</th>
                        </thead>
                        <tbody>
                            @forelse ($company->teams as $team)
                                <tr>
                                    <td>{{ $team->id }}</td>
                                    <td>{{ $team->name}}</td>
                                    <td>{{ $team->description }}</td>
                                    <td>
                                        @if ($team->active)
                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                        @else
                                            <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No Team Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

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
