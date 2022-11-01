@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Companies
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Companies
                {{-- <a href="{{ route('admin.company.create') }}" class="btn btn-danger"><i
                        class="fas fa-plus"></i></a> --}}
            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>Id</th>
                        <th>Action</th>
                        <th>Name</th>
                        <th>Total Office</th>
                        <th>Total Customer</th>
                        <th>Total Employee</th>
                        <th>Active</th>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @can('company-edit')
                                            <a class="dropdown-item"
                                                href="{{ route('admin.company.edit', $company) }}">Edit</a>
                                            @endcan

                                            @can('company-customers')
                                            <a class="dropdown-item"
                                                href="{{ route('admin.company.customers', $company) }}">Cusomers</a>
                                            @endcan
                                            @can('company-offices')
                                            <a class="dropdown-item"
                                                href="{{ route('admin.company.office', $company) }}">Offices</a>
                                            @endcan
                                            @can('company-employees')
                                            <a class="dropdown-item"
                                                href="{{ route('admin.company.employee', $company) }}">Employees</a>
                                            @endcan

                                            @can('company-delete')
                                            <form  action="{{ route('admin.company.destroy', ['company' => $company]) }}"
                                                method="POST">
                                                @method("DELETE")
                                                @csrf
                                                <input type="submit" style="background-color:transparent; border:none;"
                                                    value="Delete"
                                                    onclick="return confirm('Are you sure? you want to delete this company?');">
                                            </form>
                                            @endcan

                                        </div>
                                    </div>
                                </td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->officeLocation->count() }}</td>
                                <td>{{ $company->customers->count() }}</td>
                                <td>{{ $company->employees->count() }}</td>
                                <td>
                                    @if ($company->active)
                                        <span class="badge badge-success"> <i class="fas fa-check"></i> </span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection



@push('js')
@endpush
