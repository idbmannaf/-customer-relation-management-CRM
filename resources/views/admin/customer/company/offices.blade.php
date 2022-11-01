@extends('admin.layouts.adminMaster')
@push('title')
    | Admin Dashboard | Company Office of {{ $customer_company->name }}
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                @if (auth()->user()->can('customer-company-office-add'))
                    <div> Offices of {{ $customer_company->name }} <a
                            href="{{ route('admin.customerCompanyOffice.create', ['customer_company' => $customer_company]) }}"
                            class="btn btn-danger btn-sm"><i class="fas fa-plus"></i></a></div>
                    <div>
                @endif
                    <a href="{{ route('admin.customer_company.index') }}" class="btn btn-warning">back</a>
                </div>
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <th>Action</th>
                        <th>Active</th>
                        <th>Location Name</th>
                        <th>Company Name</th>
                        <th>Division</th>
                        <th>District</th>
                        <th>Thana</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Google Location</th>
                        {{-- <th>Image</th> --}}

                    </thead>
                    <tbody>
                        @foreach ($customer_company->offices as $office_location)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            @if (auth()->user()->can('customer-company-office-edit'))
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.customerCompanyOffice.edit', ['office' => $office_location, 'customer_company' => $customer_company]) }}">Edit</a>
                                                @endif

                                            @if (auth()->user()->can('customer-company-office-delete'))
                                                <form
                                                    action="{{ route('admin.customerCompanyOffice.delete', ['customer_company' => $customer_company,'office'=>$office_location]) }}"
                                                    method="POST">
                                                    @method("DELETE")
                                                    @csrf
                                                    <input type="submit" style="background-color:transparent; border:none;"
                                                        value="Delete"
                                                        onclick="return confirm('Are you sure? you want to delete this team?');">
                                                </form>
                                            @endif

                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($office_location->active)
                                        <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                    @endif
                                </td>
                                <td> {{ $office_location->title }}</td>
                                <td> {{ $office_location->customer_company->name }}</td>
                                <td> {{ $office_location->division ? $office_location->division->name : '' }}</td>
                                <td> {{ $office_location->district ? $office_location->district->name : '' }}</td>
                                <td> {{ $office_location->thana ? $office_location->thana->name : '' }}</td>
                                <td> {{ $office_location->lat }}</td>
                                <td> {{ $office_location->lng }}</td>
                                <td> {{ $office_location->google_location }}</td>
                                {{-- <td>
                                <img src="{{ route('imagecache', [ 'template'=>'medium','filename' => $office_location->fi() ]) }}" alt="">
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
