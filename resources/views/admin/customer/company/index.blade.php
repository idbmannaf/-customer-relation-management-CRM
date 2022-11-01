@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Companies
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Customer Companies
            </div>
        </div>
        @include('alerts.alerts')

        <div class="card-body">
            {{-- <div class="row pb-2">
                <div class="col-12 col-md-3">
                    <fieldset>
                        <legend>Bulk Upload <a href="{{ asset('img/customer.png') }}" class="badge badge-danger" title="Follow The instruction "><i class="fas fa-info"></i></a>
                        </legend>
                        <form action="{{ route('admin.customer_company.store',['type'=>'bulk_upload']) }}" enctype="multipart/form-data" method="post">
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
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>Id</th>
                        <th>Action</th>
                        <th>Name</th>
                        <th>Total Office</th>
                        {{-- <th>Total Visitor</th> --}}
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
                                            @if (auth()->user()->can('customer-company-edit'))
                                                <a class="dropdown-item"
                                                href="{{ route('admin.customer_company.edit', $company) }}">Edit</a>
                                            @endif


                                            @if (auth()->user()->can('customer-company-office'))
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.customerCompanyOffice', $company) }}">Offices<a>
                                                @endif
                                            {{-- @can('company-customers')
                                            <a class="dropdown-item"
                                                href="{{ route('admin.customer_company.customers', $company) }}">Cusomers</a>
                                            @endcan --}}
                                            @if (auth()->user()->can('customer-company-delete'))
                                                <form  action="{{ route('admin.customer_company.destroy', ['customer_company' => $company]) }}"
                                                    method="POST">
                                                    @method("DELETE")
                                                    @csrf
                                                    <input type="submit" style="background-color:transparent; border:none;"
                                                        value="Delete"
                                                        onclick="return confirm('Are you sure? you want to delete this company?');">
                                                </form>
                                            @endif


                                        </div>
                                    </div>
                                </td>
                                <td>{{ $company->name }}</td>
                                <td>
                                    {{$company->offices->count()}}
                                </td>
                                {{-- <td>
                                    {{  $company->visitors()->count()}}
                                </td> --}}
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
            {{$companies->render()}}
        </div>
    </div>
@endsection



@push('js')
@endpush
