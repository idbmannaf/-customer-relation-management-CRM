@extends('employee.layouts.employeeMaster')
@push('title')
    | Employee Dashboard | Companies
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Companies

            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            <form action="{{ route('employee.addCustomerCompany') }}" method="POST">
                @csrf
                <div class="row justify-contren-center">
                    <div class="col-12 col-md-10 m-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col 12 col-md-4">
                                        <input type="text" name="name" id="name"
                                            class="form-control @error('name') is-invald @enderror()"
                                            placeholder="Company Name here...">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col 12 col-md-4">
                                        <input type="text" name="address" id="address"
                                            class="form-control @error('address') is-invald @enderror()"
                                            placeholder="Address here...">
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col 12 col-md-4">
                                        <label for="active"> <input type="checkbox" name="active" id="active">
                                            Active</label>
                                        <input type="submit" class="btn btn-info">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>Id</th>
                        <th>Action</th>
                        <th>Name</th>
                        <th>Total Office</th>
                        <th>Active</th>
                    </thead>
                    <tbody style="height: 100px">
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

                                            @if (in_array($company->addedBy_id, $addeByArray))
                                                <a class="dropdown-item" data-toggle="modal"
                                                    data-target="#edit{{ $company->id }}" data-whatever="@fat"
                                                    href="#">Edit</a>
                                            @endif

                                            <a class="dropdown-item"
                                                href="{{ route('employee.customerCompanyOffice', $company) }}">Offices<a>

                                        </div>
                                    </div>
                                </td>
                                <td>{{ $company->name }}</td>
                                <td>
                                    {{ $company->offices ? $company->offices->count() : 0 }}
                                </td>
                                <td>
                                    @if ($company->active)
                                        <span class="badge badge-success"> <i class="fas fa-check"></i> </span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Edit Modal Start --}}
                            <div class="modal fade" id="edit{{ $company->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Company:
                                                {{ $company->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <form action="{{route('employee.updateCustomerCompany',$company)}}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                            <div class="form-group">
                                                <label for="name">Company Name</label>
                                                <input type="text" name="name" id="name"
                                                    class="form-control @error('name') is-invald @enderror()"
                                                    placeholder="Company Name here..." value="{{ $company->name }}">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Company Address</label>

                                                <input type="text" name="address" id="address"
                                                    class="form-control @error('address') is-invald @enderror()"
                                                    placeholder="Address here..." value="{{ $company->address }}">
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="ac"> <input {{ $company->active ? 'checked' : '' }}
                                                        type="checkbox" name="active" id="ac"> Active</label>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" value="Update" class="btn btn-info">
                                            </div>
                                        </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- Edit Modal End --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{$companies->render()}}
        </div>
    </div>
@endsection



@push('js')
@endpush
