<div class="table-responsive">
    <table class="table table-bordered table-sm text-nowrap">
        <thead>
            <th>Id</th>
            <th>Action</th>
            <th>Employee</th>
            <th>Email</th>
            <th>Temp. Password</th>
            <th>Customer Company</th>
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
            @forelse ($others_customer as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>
                        <div class="dropdown">

                            <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button"
                                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </a>


                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('employee.othersCustomerEdit', $customer) }}">Edit</a>
                                <a class="dropdown-item"
                                href="{{ route('employee.myCustomerOffices', $customer) }}">Offices</a>

                            </div>
                        </div>

                    </td>

                    <td>{{$customer->employee? $customer->employee->name : ''}}</td>
                    <td>{{ $customer->user ? $customer->user->username : ''}}</td>
                    <td>{{ $customer->user ? $customer->user->temp_password : '' }}</td>
                    <td>{{ $customer->company ? $customer->company->name : '' }}</td>
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

{{ $others_customer->appends(['q'=>$q])->render() }}
