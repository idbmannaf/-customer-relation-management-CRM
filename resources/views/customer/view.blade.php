@extends('customer.layouts.customerMaster')
@section('content')
<div class="row">
    <div class="col-12 col-md-6 m-auto">
       <div class="card">
           <div class="card-header">My Details</div>
           <div class="card-body">
            <div class="table-responsive">
                <table class="table table-borderd table-sm text-nowrap">
                    <tr>
                        <th>Customer Name</th>
                        <td>{{$customer->name}}</td>
                    </tr>
                    <tr>
                        <th>Customer Email</th>
                        <td>{{$customer->email}}</td>
                    </tr>
                    <tr>
                        <th>Customer Code</th>
                        <td>{{$customer->customer_code}}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{$customer->phone}}</td>
                    </tr>
                    <tr>
                        <th>Area</th>
                        <td>{{$customer->area}}</td>
                    </tr>
                    <tr>
                        <th>Division</th>
                        <td>{{$customer->division}}</td>
                    </tr>
                    <tr>
                        <th>District</th>
                        <td>{{$customer->district}}</td>
                    </tr>
                    <tr>
                        <th>Thana</th>
                        <td>{{$customer->thana}}</td>
                    </tr>
                    <tr>
                        <th>District</th>
                        <td>{{$customer->district}}</td>
                    </tr>
                    <tr>
                        <th>Customer Type</th>
                        <td>{{$customer->customer_type}}</td>
                    </tr>
                    <tr>
                        <th>Contact Person Name</th>
                        <td>{{$customer->designation}}</td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td>{{$customer->mobile}}</td>
                    </tr>
                    <tr>
                        <th>Ledger Balance</th>
                        <td>{{$customer->ledger_balanc}}</td>
                    </tr>
                    <tr>
                        <th>Active</th>
                        <td>
                            @if ($customer->active)
                            <span class="badge badge-success">Actived</span>
                            @else
                            <span class="badge badge-danger">InActived</span>

                        @endif
                        </td>
                    </tr>
                </table>
            </div>
           </div>
       </div>
    </div>
</div>
@endsection
