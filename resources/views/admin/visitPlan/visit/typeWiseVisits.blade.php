@extends('admin.layouts.adminMaster')
@push('title')
    Admin Dashboard |Employee Visits
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">{{ ucfirst($type) }} {{ $status ?? '' }} Visits
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>Visit Plan Date</th>
                            <th>Action</th>
                            {{-- <th>Sibling</th> --}}
                            <th>Visit Status</th>
                            <th>Status</th>
                            <th>Employee</th>
                            <th>Customer</th>
                            <th>Offer/Quatation</th>
                            <th>Visit Date</th>
                            <th>Pupose Of Visit </th>
                            <th>Payment Collection Date </th>
                            <th>Payment Maturity Date </th>
                            <th>Achievment </th>
                            <th>Sale Amount </th>
                            <th>Collection Detils </th>
                            <th>Collection Amount</th>
                            <th>Current Ledger balance </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visits as $visit)
                            <tr>
                                <td>{{ $visit->date_time }} {{ $visit->addeBy_id }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if ($visit->status == 'pending')
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.customerVisitEdit', ['visit_plan' => $visit->visit_plan, 'visit' => $visit]) }}">Edit</a>
                                            @endif
                                            <a class="dropdown-item"
                                                href="{{ route('admin.customerVisitview', ['visit_plan' => $visit->visit_plan, 'visit' => $visit]) }}">Details</a>

                                            @if ($visit->status == 'pending')
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'approved']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Approved this Visit?')">Approved
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'rejected']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Reject this Visit?')">Rejected
                                                </a>
                                            @endif
                                            @if ($visit->status == 'approved' && $visit->offer_id)
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'completed']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Approv this Visit?')">Completed
                                                </a>
                                            @endif
                                            @if ($visit->visit_plan->visited_at)
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.convayances', ['visit' => $visit]) }}">Convayance
                                                    Bill Claim
                                                </a>
                                            @endif

                                            @if (($visit->status == 'approved' || $visit->status == 'completed') && $visit->offer_id)
                                                @if ($visit->visit_plan->service_type == 'service')
                                                    @if ($visit->offer_quotation->items()->where('product_type', 'spare_parts')->count() > 0)
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.requisition', ['type' => 'spear_parts', 'visit' => $visit]) }}">Spear
                                                            Part Requisition </a>
                                                    @endif

                                                    @if ($visit->offer_quotation->items()->where('product_type', 'products')->count() > 0)
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.requisition', ['type' => 'product', 'visit' => $visit]) }}">Product
                                                            Requisition</a>
                                                    @endif

                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.requisition', ['type' => 'inhouse_product', 'visit' => $visit]) }}">Inhouse
                                                        Work Requisition</a>
                                                @elseif ($visit->visit_plan->service_type == 'sales')
                                                    @if ($visit->offer_quotation->items()->where('product_type', 'spare_parts')->count() > 0)
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.requisition', ['type' => 'spear_parts', 'visit' => $visit]) }}">Spear
                                                            Part Requisition </a>
                                                    @endif

                                                    @if ($visit->offer_quotation->items()->where('product_type', 'products')->count() > 0)
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.requisition', ['type' => 'product', 'visit' => $visit]) }}">Product
                                                            Requisition</a>
                                                    @endif
                                                @endif

                                                @if ($visit->visit_plan->call_id)
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.requisition', ['type' => 'warranty_claim', 'visit' => $visit]) }}">Waranty
                                                        Claim
                                                    </a>
                                                @endif
                                            @endif
                                        </div>

                                    </div>
                                </td>
                                <td>
                                    @if ($visit->visit_plan->visited_at)
                                        <div class="badge badge-success">Visited</div>
                                    @else
                                        <div class="badge badge-warning">Not Visited</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($visit->status == 'pending')
                                        <span class="text-danger">Pending</span>
                                    @elseif ($visit->status == 'confirmed')
                                        <span class="text-warning">Confirmed</span>
                                    @elseif ($visit->status == 'approved')
                                        <span class="text-success">Approved</span>
                                    @elseif ($visit->status == 'completed')
                                        <span class="text-success">Completed</span>
                                    @endif
                                </td>
                                <td>{{ $visit->employee ? $visit->employee->name : '' }}</td>
                                <td>{{ $visit->customer ? $visit->customer->customer_name : '' }}</td>
                                <td>
                                    @if (($visit->status == 'approved' || $visit->status == 'completed') && !$visit->visit_plan->invoice_id)
                                        @if (!$visit->offer_id && $visit->visit_plan->service_type != 'collection')
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('admin.customerOffer.create', ['customer' => $visit->visit_plan->customer, 'visit' => $visit]) }}">
                                                Make
                                                Offer/Quotation</a>
                                        @elseif($visit->offer_id)
                                            <a href="{{ route('admin.customerOfferDetails', $visit->offer_id) }}"><i
                                                    class="fas fa-eye"></i>view</a>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $visit->date_time }}</td>
                                <td>{{ $visit->purpose_of_visit }}</td>
                                <td>{{ $visit->payment_collection_date }}</td>
                                <td>{{ $visit->payment_maturity_date }}</td>
                                <td>{{ $visit->achievement }}</td>
                                <td>
                                    @if (count($visit->sales_items) > 0)
                                        <a href="" class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#vs-{{ $visit->id }}"
                                            data-whatever="@fat">{{ $visit->sale_amount }}</a>
                                    @else
                                        {{ $visit->sale_amount }}
                                    @endif
                                </td>
                                <td>{{ $visit->collection_details }}</td>
                                <td>{{ $visit->collection_amount }}</td>
                                <td>{{ $visit->current_ledger_balance }}</td>

                            </tr>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
