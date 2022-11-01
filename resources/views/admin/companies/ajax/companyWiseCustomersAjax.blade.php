<div class="table-responsive">
    <table class="table table-bordered table-sm text-nowrap">
        <thead>
            <th>Id</th>
            <th>Company</th>
            <th>Action</th>
            <th>Email</th>
            <th>Temp. Password</th>
            <th>Company Name</th>
            <th>Customer Name</th>
            <th>Adderess</th>
            <th>Active</th>
        </thead>
        <tbody>
            @forelse ($company->customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->company ? $customer->company->name : '' }}</td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button"
                                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('admin.customer.edit', $customer) }}">Edit</a>
                                <form action="{{ route('admin.customer.destroy', $customer) }}" method="POST">
                                    @method("DELETE")
                                    @csrf
                                    <input type="submit" style="background-color:transparent; border:none;"
                                        value="Delete"
                                        onclick="return confirm('Are you sure? you want to delete this team?');">
                                </form>

                            </div>
                        </div>

                    </td>
                    <td>{{ $customer->user ? $customer->user->email : '' }}</td>
                    <td>{{ $customer->user ? $customer->user->temp_password : '' }}</td>
                    <td>{{ $customer->company_name }}</td>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ $customer->address }}</td>
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

{{-- {{ $company->customers->appends(['q' => $q])->render() }} --}}
