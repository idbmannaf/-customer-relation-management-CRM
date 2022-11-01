@extends('employee.layouts.employeeMaster')
@push('title')
    | Emplyee Dashboard | My Customers
@endpush

@push('css')
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="d-flex justify-content-between">
                <div> My Customers
                    <a href="{{route('employee.myCustomers.create')}}" class="btn btn-danger"> <i class="fas fa-plus"></i></a>
                </div>

            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">

                {{-- <div class="row pb-2">
                    <div class="col-12 col-md-3">
                        <fieldset>
                            <legend>Bulk Upload <a href="{{ asset('img/customer.png') }}" class="badge badge-danger"  title="Follow The instruction "><i class="fas fa-info"></i></a>
                            </legend>
                            <form action="{{ route('admin.importCustomer') }}" enctype="multipart/form-data" method="post">
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



                {{-- <div class="d-flex justify-content-end">
                    <div class="">
                        <div class="card-body ">
                            <input type="search" class="form-control" id="search"
                                data-url={{ route('admin.customerSearch') }} name="q" placeholder="Search">
                        </div>
                    </div>
                </div> --}}



            <div class="showCustomer">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-nowrap">
                        <thead>
                            <th>Id</th>
                            {{-- <th>Action</th> --}}
                            <th>Employee</th>
                            <th>Company</th>
                            <th>Email</th>
                            {{-- <th>Temp. Password</th> --}}
                            <th>Customer Name</th>
                            <th>Customer Code</th>
                            <th>Client Address</th>
                            <th>Phone</th>
                            <th>Area</th>
                            <th>Division</th>
                            <th>District</th>
                            <th>Thana</th>
                            <th>Customer Type</th>
                            <th>Contact Person name </th>
                            <th>Designation </th>
                            <th>Mobile </th>
                            <th>Ledger balance </th>
                            <th>Active</th>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    {{-- <td>
                                        <div class="dropdown">
                                            <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button"
                                                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @can('customer-edit')
                                                <a class="dropdown-item" href="{{ route('admin.customer.edit', $customer) }}">Edit</a>
                                                @endcan

                                                @can('customer-delete')
                                                <form action="{{ route('admin.customer.destroy', $customer) }}" method="POST">
                                                    @method("DELETE")
                                                    @csrf
                                                    <input type="submit" style="background-color:transparent; border:none;"
                                                        value="Delete"
                                                        onclick="return confirm('Are you sure? you want to delete this team?');">
                                                </form>
                                                @endcan

                                            </div>
                                        </div>

                                    </td> --}}
                                    <td>{{$customer->employee? $customer->employee->name ."()".$customer->employee->employee_id.")" : '' }}</td>
                                    <td>{{$customer->employee? ($customer->employee->company ? $customer->employee->company->name : '') : ''}}</td>
                                    <td>{{ $customer->user ? $customer->user->username : ''}}</td>
                                    {{-- <td>{{ $customer->user ? $customer->user->temp_password : '' }}</td> --}}
                                    <td>{{ $customer->customer_name }}</td>
                                    <td>{{ $customer->customer_code }}</td>
                                    <td>{{ $customer->client_address }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->area }}</td>
                                    <td>{{ $customer->division }}</td>
                                    <td>{{ $customer->district }}</td>
                                    <td>{{ $customer->thana }}</td>
                                    <td>{{ $customer->customer_type }}</td>
                                    <td>{{ $customer->contact_person_name }}</td>
                                    <td>{{ $customer->designation }}</td>
                                    <td>{{ $customer->mobile }}</td>
                                    <td>{{ $customer->ledger_balance }}</td>
                                    <td>
                                        @if ($customer->active)
                                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                                        @else
                                            <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No Customer Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $customers->render() }}

            </div>
        </div>
    </div>
@endsection



@push('js')
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
@endpush
