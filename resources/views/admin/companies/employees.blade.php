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
                <div> Employees of {{$company->name}} </div>
                <div>
                    <a href="{{ route('admin.employee.create') }}" class=""><i class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            {{-- <a href="" class="btn btn-info" data-toggle="modal" data-target="#bulkUpload">Bulk Upload</a>   <a href="" class="badge badge-info" data-toggle="modal" data-target="#info"><i class="fas fa-info"></i></a>
            @include('admin.employee.modal.bulkUpload') --}}
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>Action</th>
                        <th>Active</th>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Joining Date</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Company</th>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)

                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.employee.edit', $employee) }}">Edit</a>
                                            <form action="{{ route('admin.employee.destroy', $employee) }}" method="POST">
                                                @method("DELETE")
                                                @csrf
                                                <input type="submit" style="background-color:transparent; border:none;"
                                                    value="Delete"
                                                    onclick="return confirm('Are you sure? you want to delete this team?');">
                                            </form>

                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if ($employee->active)
                                        <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                                <td>{{$employee->employee_id}}</td>
                                <td>{{$employee->name}}</td>
                                <td>{{$employee->joining_date}}</td>
                                <td>{{$employee->designation ? $employee->designation->title : ''}}</td>
                                <td>{{$employee->department ? $employee->department->title : ''}}</td>
                                <td>{{$employee->company ? $employee->company->name : ''}}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- {{$employees->render()}} --}}
        </div>
    </div>
@endsection


