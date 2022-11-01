@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Visits
@endpush


@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title"> {{ ucfirst($type ?? 'all') }} {{ $status ?? '' }} Visits
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
                            @if (auth()->user()->employee->team_admin)
                                <th>Employee</th>
                            @endif
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
                            <tr @if (count($visits) < 4) style="height: 250px;" @endif>
                                <td> {{ $visit->visit_plan->date_time }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-success dropdown-toggle btn-sm" href="#" role="button"
                                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if (($visit->addedBy_id == auth()->user()->id ||
                                                auth()->user()->employee->isMyMember($visit->employee_id)) &&
                                                $visit->status == 'pending')
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitEdit', ['visit_plan' => $visit->visit_plan, 'visit' => $visit]) }}">Edit</a>
                                            @endif
                                            <a class="dropdown-item"
                                                href="{{ route('employee.customerVisitview', ['visit_plan' => $visit->visit_plan, 'visit' => $visit]) }}">Details</a>

                                            @if (auth()->user()->employee->isMyMember($visit->employee_id) && $visit->status == 'pending')
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'approved']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Approv this Visit?')">Approved
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'rejected']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Reject this Visit?')">Rejected
                                                </a>
                                            @endif
                                            @if (auth()->user()->employee->isMyMember($visit->employee_id) && $visit->status == 'confirmed')
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'approved']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Approv this Visit?')">Approved
                                                </a>
                                            @endif

                                            @if ((auth()->user()->employee->isMyMember($visit->employee_id) ||
                                                auth()->user()->employee_id == $visit->employee_id) &&
                                                $visit->status == 'approved' &&
                                                $visit->offer_id)
                                                @if ($visit->visit_plan && $visit->visit_plan->service_type == 'sales')
                                                @else
                                                    <a class="dropdown-item"
                                                        href="{{ route('employee.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'completed']) }}"
                                                        onclick="return confirm('Are you Sure? you want to Complete this Visit?')">Completed
                                                    </a>
                                                @endif
                                            @endif
                                            @if ($visit->visit_plan->visit_start_at && $visit->status == 'approved')
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.convayances', ['visit' => $visit]) }}">Convayance
                                                    Bill Claim
                                                </a>
                                            @endif


                                            @if ($visit->status == 'approved' || $visit->status == 'completed')
                                                @if ($visit->visit_plan->service_type == 'service')
                                                    <a class="dropdown-item"
                                                        href="{{ route('employee.requisition', ['type' => 'spear_parts', 'visit' => $visit]) }}">Spear
                                                        Part Requisition </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('employee.requisition', ['type' => 'product', 'visit' => $visit]) }}">Product
                                                        Requisition</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('employee.requisition', ['type' => 'inhouse_product', 'visit' => $visit]) }}">Inhouse
                                                        Work Requisition</a>
                                                @elseif ($visit->visit_plan->service_type == 'sales')
                                                    @if ($visit->offer_quotation)
                                                        @if ($visit->offer_quotation->items()->where('product_type', 'spare_parts')->count() > 0)
                                                            <a class="dropdown-item"
                                                                href="{{ route('employee.requisition', ['type' => 'spear_parts', 'visit' => $visit]) }}">Spear
                                                                Part Requisition </a>
                                                        @endif

                                                        @if ($visit->offer_quotation->items()->where('product_type', 'products')->count() > 0)
                                                            <a class="dropdown-item"
                                                                href="{{ route('employee.requisition', ['type' => 'product', 'visit' => $visit]) }}">Product
                                                                Requisition</a>
                                                        @endif
                                                    @endif
                                                @endif
                                                @if ($visit->visit_plan->call_id)
                                                    <a class="dropdown-item"
                                                        href="{{ route('employee.requisition', ['type' => 'warranty_claim', 'visit' => $visit]) }}">Waranty
                                                        Claim
                                                    </a>
                                                @endif
                                            @endif

                                        </div>

                                    </div>
                                </td>
                                {{-- <td>
                                    @if ($visit->sibling_visit_id)
                                        <a href="{{ route('employee.allOfMyTeamMemberVisits', ['type' => $type ?? 'all', 'sibling' => $visit->sibling_visit_id]) }}"
                                            class="btn btn-sm btn-primary">{{ $visit->sibling_visit_id }}</a>
                                    @endif

                                </td> --}}
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
                                @if (auth()->user()->employee->team_admin)
                                    <td>{{ $visit->employee ? $visit->employee->name : '' }}</td>
                                @endif
                                <td>{{ $visit->customer ? $visit->customer->customer_name : '' }}</td>
                                <td>
                                    @if (($visit->status == 'approved' || $visit->status == 'completed') && !$visit->visit_plan->invoice_id)
                                        @if (!$visit->offer_id && $visit->visit_plan->service_type != 'collection')
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('employee.customerOffer.create', ['customer' => $visit->customer, 'visit' => $visit]) }}">
                                                Make
                                                Offer/Quotation</a>
                                        @elseif($visit->offer_id)
                                            <a href="{{ route('employee.customerOfferDetails', $visit->offer_id) }}"><i
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
                            @if (count($visit->sales_items) > 0)
                                @php
                                    $visit_plan = $visit->visit_plan;
                                @endphp
                                @include('global.saleModal')
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $visits->appends(request()->all())->render() }}

        </div>
    </div>
@endsection
