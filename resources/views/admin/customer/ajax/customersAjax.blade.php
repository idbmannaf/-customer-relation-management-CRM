<div class="table-responsive">
    <table class="table table-bordered table-sm text-nowrap">
        <thead>
            <th>Id</th>
            <th>Action</th>
            <th>Employee</th>
            <th>Company</th>
            <th>Email</th>
            <th>Mobile </th>
            <th>Temp. Password</th>
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
            <th>Ledger balance </th>
            <th>Active</th>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button"
                                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @can('customer-edit')
                                <a class="dropdown-item" href="{{ route('admin.customer.edit',['customer'=>$customer]) }}">Edit</a>
                                @endcan
                                <a class="dropdown-item" href="{{ route('admin.customerOffer',['customer'=>$customer]) }}">Offers</a>
                                @can('customer-delete')
                                <form action="{{ route('admin.customer.destroy', ['customer'=>$customer]) }}" method="POST">
                                    @method("DELETE")
                                    @csrf
                                    <input type="submit" style="background-color:transparent; border:none;"
                                        value="Delete"
                                        onclick="return confirm('Are you sure? you want to delete this team?');">
                                </form>
                                @endcan

                            </div>
                        </div>

                    </td>
                    <td>{{$customer->employee? $customer->employee->name ."()".$customer->employee->employee_id.")" : '' }}</td>
                    <td>{{$customer->company? $customer->company->name : ''}}</td>
                    <td>
                        @if ($customer->user)
                            @if ($customer->user->username)
                                <a class="btn btn-success btn-xs" href="tel:{{ $customer->user->username }}"><i
                                        class="fas fa-envelope"></i>{{ $customer->user->username }}</a>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($customer->mobile)
                            <a class="btn btn-warning btn-xs" href="tel:{{ $customer->mobile }}"><i
                                    class="fas fa-phone-volume"></i>{{ $customer->mobile }}</a>
                        @endif
                    </td>
                    <td>{{ $customer->user ? $customer->user->temp_password : '' }}</td>
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

{{ $customers->appends(['q' => $q])->render() }}
