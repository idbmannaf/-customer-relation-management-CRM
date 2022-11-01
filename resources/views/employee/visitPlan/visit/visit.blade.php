@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Visits
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Visits Of {{ $visit_plan->date_time }}
                @if ($visit_plan->status != 'completed')
                    <a class="btn btn-danger btn-sm" href="{{ route('employee.customerVisitCreate', $visit_plan) }}">Visit
                        Report</a>
                @endif

                @if ($visit_plan->call)
                    <a href="" class="btn btn-success btn-xs" data-toggle="modal"
                        data-target="#vp-{{ $visit_plan->id }}" data-whatever="@fat">Service Call Details</a>
                @endif
                @if ($visit_plan->customer_office_location_id)
                    <a href="javascript:void(0)" class="btn btn-danger">Already Checked</a>
                @else
                    <a href="{{ route('employee.customerVisited', $visit_plan) }}" class="btn btn-light btn-xs"
                        onclick="return confirm('are you sure? you checked This Customer?')">Checked In</a>
                @endif
            </div>
        </div>
        @include('alerts.alerts')

        <!-- Modal Fullscreen xl -->
        @include('global.customerOfferModal')
        @if ($visit_plan->invoice_id)
            {{-- @include('global.invoice_modal') --}}
        @endif
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>Visit Plan Date</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Customer</th>
                            <th>Offer/Quatation</th>
                            <th>Visit Date</th>
                            <th>Pupose Of Visit </th>
                            <th>Sale Amount </th>
                            <th>Collection Amount</th>
                            <th>Remarks</th>
                            <th>Current Ledger balance </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visits as $visit)
                            <tr @if (count($visits) < 4) style="height: 250px;" @endif>
                                <td>{{ $visit->visit_plan->date_time }}</td>
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
                                                    href="{{ route('employee.customerVisitEdit', ['visit_plan' => $visit_plan, 'visit' => $visit]) }}">Edit</a>
                                            @endif
                                            <a class="dropdown-item"
                                                href="{{ route('employee.customerVisitview', ['visit_plan' => $visit_plan, 'visit' => $visit]) }}">Details</a>

                                            @if (auth()->user()->employee->team_admin &&  auth()->user()->employee->company->access_all_call &&
                                                $visit->status == 'pending')
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitStatusUpdate', ['visit_plan' => $visit_plan, 'visit' => $visit, 'status' => 'approved']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Approved this Visit?')">Approved
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitStatusUpdate', ['visit_plan' => $visit_plan, 'visit' => $visit, 'status' => 'rejected']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Reject this Visit?')">Rejected
                                                </a>
                                            @endif
                                            @if ((auth()->user()->employee->isMyMember($visit->employee_id) || auth()->user()->employee_id) && $visit->status == 'approved' && $visit->offer_id)
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.customerVisitStatusUpdate', ['visit_plan' => $visit->visit_plan, 'visit' => $visit, 'status' => 'completed']) }}"
                                                    onclick="return confirm('Are you Sure? you want to Approv this Visit?')">Completed
                                                </a>
                                            @endif
                                            @if ($visit->visit_plan->visit_start_at)
                                                <a class="dropdown-item"
                                                    href="{{ route('employee.convayances', ['visit' => $visit]) }}">Convayance
                                                    Bill Claim
                                                </a>
                                            @endif

                                            @if (($visit->status == 'approved' || $visit->status == 'completed') && $visit->offer_id)
                                                @if ($visit->visit_plan->service_type =='service')
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

                                                    <a class="dropdown-item"
                                                        href="{{ route('employee.requisition', ['type' => 'inhouse_product', 'visit' => $visit]) }}">Inhouse
                                                        Work Requisition</a>
                                                @elseif ($visit->visit_plan->service_type == 'sales')
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

                                            @if ($visit->visit_plan->call && $visit->visit_plan->call->type == 'warranty')
                                                    <a class="dropdown-item"
                                                        href="{{ route('employee.requisition', ['type' => 'warranty_claim', 'visit' => $visit]) }}">Waranty
                                                        Claim
                                                    </a>
                                                @endif
                                        </div>

                                    </div>
                                </td>

                                <td>
                                    @if ($visit->status == 'pending')
                                        <span class="text-warning">Pending</span>
                                    @elseif ($visit->status == 'confirmed')
                                        <span class="text-success">Approved</span>
                                    @elseif ($visit->status == 'approved')
                                        <span class="text-success">Approved</span>
                                    @elseif ($visit->status == 'rejected')
                                        <span class="text-danger">Rejected</span>
                                    @elseif ($visit->status == 'completed')
                                        <span class="text-success">Completed</span>
                                    @endif
                                </td>
                                <td>{{ $visit->customer ? $visit->customer->customer_name : '' }}</td>
                                <td>
                                    @if (($visit->status == 'approved' || $visit->status == 'completed') && !$visit_plan->invoice_id)
                                        @if (!$visit->offer_id && $visit_plan->service_type !='collection')
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('employee.customerOffer.create', ['customer' => $visit_plan->customer, 'visit' => $visit]) }}">
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
                                <td>
                                    @if (count($visit->sales_items) > 0)
                                        <a href="" class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#vs-{{ $visit->id }}"
                                            data-whatever="@fat">{{ $visit->sale_amount }}</a>
                                    @else
                                        {{ $visit->sale_amount }}
                                    @endif
                                </td>

                                <td>{{ $visit->collection_amount }}</td>
                                <td>{{ $visit->remarks }}</td>
                                <td>{{ $visit->current_ledger_balance }}</td>


                                @if (count($visit->sales_items) > 0)
                                    @include('global.saleModal')
                                @endif

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($visit_plan->call)
            @include('global.callModal')
        @endif
    </div>
@endsection


@push('js')
    <!-- summernote css/js -->
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            $('.employee-select').select2({
                theme: 'bootstrap4',
                // minimumInputLength: 1,
                ajax: {
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        // alert(data[0].s);
                        var data = $.map(data, function(obj) {
                            obj.id = obj.id || obj.id;
                            return obj;
                        });
                        var data = $.map(data, function(obj) {
                            obj.text = obj.name + "(" + obj.model + ")";
                            return obj;
                        });
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                },
            });


        });
    </script>
    <script type="text/javascript">
        $('#body').summernote({
            height: 400
        });
        $('#terms_and_condition').summernote({
            height: 300
        });
        $('#to').summernote({
            height: 200
        });
        $('#signature').summernote({
            height: 100
        });
    </script>
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
    <script>
        $(document).on('click', '.addBtn', function(e) {

            e.preventDefault();
            var product_id = $(this).closest('.row').find('#product').val();
            if (!product_id) {
                $('.productError').text('Select A product');
                return;
            }
            $('.productError').text('');

            var url = $(this).attr('data-url');
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    product_id: product_id,
                },
                success: function(res) {
                    if (res.success) {
                        $('.showItem').append(res.html);
                    }
                }
            })
        })

        function changeOfferItem(e, type) {
            var that = $(e);
            var url = that.closest('.mother').attr('data-url');
            if (type == 'quantity') {
                if (that.val() < 1) {
                    alert('Quantity Must be at last 1');
                    return;
                }
                that.closest('.motherCard').find('.qtyError').text('');
            }
            var value = that.val();
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    value: value,
                    type: type
                },
                success: function(res) {
                    if (res.success) {
                        if (res.total_price) {
                            that.closest('.mother').find('.total_price').text(res.total_price);
                        }
                    }
                }
            })
        }
        $(document).on('click', '.deleteItem', function(e) {
            e.preventDefault();
            var that = $(this);
            var url = that.attr('href');
            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    if (res.success) {
                        that.closest('.mother').remove();
                    }
                }
            })
        })
    </script>
@endpush
