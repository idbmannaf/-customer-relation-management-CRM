<div class="table-responsive">
    <table class="table table-bordered table-sm text-nowrap">
        <thead>
            <th>Action</th>
            <th>Active</th>
            <th>Location Name</th>
            <th>Company Name</th>
            <th>AMC Number</th>
            <th>Billing Preiod</th>
            <th>Item Code</th>
            <th>Location Type</th>
            <th>Booth ID</th>
            <th>Booth Name</th>
            <th>Mobile Number</th>
            <th>UPS Brand</th>
            <th>Model</th>
            <th>Capacity</th>
            <th>Battery Brand</th>
            <th>Battery AH</th>
            <th>Battery QTY</th>
            <th>Installation Date</th>
            <th>Warrenty Exipred Date</th>
            <th>AMC Amount Per Month</th>
            <th>Serial No</th>
            <th>Address</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Division</th>
            <th>District</th>
            <th>Thana</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Google Location</th>
            <th>Asset</th>
            <th>Active</th>
            {{-- <th>Image</th> --}}

        </thead>
        <tbody>

            @foreach ($office_locations as $office_location)
                <tr>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item"
                                        href="{{ route('employee.myCustomerOfficeEdit', ['customer'=>$customer,'office'=>$office_location]) }}">Edit</a>

                                {{-- @can('office-location-delete')
                                    <form action="{{ route('admin.customerOffice.delete', $office_location) }}"
                                        method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <input type="submit" style="background-color:transparent; border:none;"
                                            value="Delete"
                                            onclick="return confirm('Are you sure? you want to delete this item?');">
                                    </form>
                                @endcan --}}

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
                    <td>
                        {{ $office_location->customer_company ? $office_location->customer_company->name : '' }}
                    </td>
                    <td>{{ $office_location->amc_number }}</td>
                    <td>{{ $office_location->billing_period }}</td>
                    <td>{{ $office_location->item_code }}</td>
                    <td>{{ $office_location->location_type }}</td>
                    <td>{{ $office_location->booth_id }}</td>
                    <td>{{ $office_location->booth_name }}</td>
                    <td>{{ $office_location->mobile_number }}</td>
                    <td>{{ $office_location->ups_brand }}</td>
                    <td>{{ $office_location->model }}</td>
                    <td>{{ $office_location->capacity }}</td>
                    <td>{{ $office_location->battery_brand }}</td>
                    <td>{{ $office_location->battery_ah }}</td>
                    <td>{{ $office_location->battery_qty }}</td>
                    <td>{{ $office_location->installation_date }}</td>
                    <td>{{ $office_location->warrenty_exipred_date }}</td>
                    <td>{{ $office_location->amc_amount_per_month }}</td>
                    <td>{{ $office_location->serial_no }}</td>
                    <td>{{ $office_location->address }}</td>
                    <td>{{ $office_location->start_date }}</td>
                    <td>{{ $office_location->end_date }}</td>

                    <td> {{ $office_location->division ? $office_location->division->name : '' }}</td>
                    <td> {{ $office_location->district ? $office_location->district->name : '' }}</td>
                    <td> {{ $office_location->thana ? $office_location->thana->name : '' }}</td>

                    <td> {{ $office_location->lat }}</td>
                    <td> {{ $office_location->lng }}</td>
                    <td> {{ $office_location->google_location }}</td>
                    <td>

                        @if ($office_location->asset)
                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                        @endif

                    </td>
                    <td>
                        @if ($office_location->active)
                            <span class="badge badge-success"><i class="fas fa-check"></i></span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-times"></i></span>
                        @endif
                    </td>
                    {{-- <td>
                                <img src="{{ route('imagecache', [ 'template'=>'medium','filename' => $office_location->fi() ]) }}" alt="">
                                </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $office_locations->appends(['q' => $q])->render() }}
