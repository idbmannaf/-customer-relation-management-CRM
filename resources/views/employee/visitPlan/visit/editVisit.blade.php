@extends('employee.layouts.employeeMaster')
@push('title')
    Emplyee Dashboard |Create Visit
@endpush
@push('css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
    <style>
        .motherCard {
            position: relative;
        }

        .croseBtn {
            position: absolute;
            top: 1;
            right: 6px;
        }

        .service_log input.form-control {
            border: none;
            border-bottom: 1px solid;
            border-bottom-style: dotted;
            outline: none;
        }

        .service_log input.form-control:focus-visible {
            border: none !important;
            border-bottom: 1px solid !important;
            border-bottom-style: dotted !important;

            outline: none;
        }

        .item_bottom {
            display: flex;
            align-items: self-end;
            margin-top: 20px;
            font-weight: 500 !important;
        }

        .bg-head {
            background-color: #6c757d3d;
            color: black;
            margin: 10px 0px;
            padding: 3px;
            font-weight: 500;
        }

        .flex-auto {
            flex: auto;
        }

        .call_details {
            padding: 2px 5px;
            border: 1px solid #6c757d3d;
            font-weight: 600 !important;

        }

        table input {
            overflow: visible !important;
            width: 100% !important;
        }


        table.table.myt td {
            border: navajowhite;
            padding-top: 0px;
        }
    </style>

    @if (Agent::isMobile())
        <style>
            .requirment table td {
                display: block !important;
                padding-bottom: 2px !important;
                width: 100% !important;
                text-align: center;
            }

            .requirment table td a {
                widows: 100% !important;
            }
        </style>
    @endif
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Create Visits Of {{ $visit_plan->date_time }}

            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('employee.customerVisitUpdate', ['visit_plan' => $visit_plan, 'visit' => $visit]) }}"
                method="POST">
                @csrf


                <div class="card">
                    <div class="col-12 pb-3">
                        <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat"
                            class="btn btn-info">Customer
                            Details</a>
                        @if ($visit_plan->call)
                            <a href="" class="btn btn-success  float-right" data-toggle="modal"
                                data-target="#vp-{{ $visit_plan->id }}" data-whatever="@fat">Call
                                Details</a>
                        @endif
                    </div>
                    @if (has_in_array($visit_plan->service_type, 'service'))
                        @include('employee.visitPlan.visit.part.edit.service')
                    @endif
                    @if (has_in_array($visit_plan->service_type, 'sales'))
                        @include('employee.visitPlan.visit.part.edit.sales')
                    @endif
                    @if (has_in_array($visit_plan->service_type, 'collection'))
                        @include('employee.visitPlan.visit.part.edit.collection')
                    @endif
                    @if (has_in_array($visit_plan->service_type, 'delivery'))
                        @include('employee.visitPlan.visit.part.edit.delivery')
                    @endif



                    <div class="row">

                        <div class="remarks pt-2 col-12">
                            <div class="">
                                <label for="visit_attachment"> Visit Attachment:</label>
                                <input type="hidden" name="visit_plan_id" value="{{ $visit_plan->id }}">
                                <input type="file" multiple id="file" name="file[]"
                                    data-url="{{ route('global.fileStore', ['type' => 'visit', 'visit' => $visit->id]) }}">
                            </div>
                            <div class="images">
                                @foreach ($visit->files as $attachment)
                                    <div class="attachment">
                                        <span class="serial"></span><a class="badge badge-success"
                                            href="{{ route('imagecache', ['template' => 'original', 'filename' => $attachment->name]) }}">
                                            {{ $attachment->name }}</a> <a
                                            href="{{ route('global.fileDelete', ['file' => $attachment->id]) }}"
                                            class="badge badge-danger deleteAttachment"><i class="fas fa-times"></i></a>
                                    </div>
                                    {{-- <a class="badge badge-success" href="{{ route('imagecache', [ 'template'=>'original','filename' => $attachment->name ]) }}">{{$loop->index + 1}}. {{$attachment->name}}</a> <a href="{{route('global.fileDelete',['file'=>$attachment->id])}}" onclick="return confirm('Are you sure? You want to delete this file?');" class="badge badge-danger"><i class="fas fa-times"></i></a> <br> --}}
                                @endforeach
                            </div>

                        </div>

                        <div class="remarks pt-2 col-12">
                            <div class="">
                                <label for="remarks" class="d-flex align-items-center"> Remarks:</label>
                                <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="3">{{ $visit->remarks }}</textarea>
                            </div>
                        </div>
                        <div class="purpose_of_visit pt-2 col-12">
                            <div class="">
                                <label for="purpose_of_visit" class="d-flex align-items-center">Purpose Of Visit:</label>
                                <textarea name="purpose_of_visit" id="purpose_of_visit" class="form-control" cols="30" rows="3">{{ $visit->purpose_of_visit }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 pt-2 m-auto">
                            <input type="submit" value="Update" class="btn btn-info">
                        </div>
                    </div>

                    @if ($visit_plan->call)
                        @include('global.callModal')
                    @endif
                    @include('global.customerModal')
                    {{-- <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-gray">Visit Plan Part</div>
                            <div class="card-body">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat"
                                                class="btn btn-info">Customer Details</a>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="date">Date</label>
                                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                                name="date"
                                                value="{{ \Carbon\Carbon::parse($visit_plan->date_time)->format('Y-m-d') }}">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="time">Time</label>
                                            <input type="time" class="form-control @error('time') is-invalid @enderror"
                                                name="time"
                                                value="{{ \Carbon\Carbon::parse($visit_plan->date_time)->format('H:i') }}">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="payment_collection_date">Payment Collection Date</label>
                                            <input type="date"
                                                class="form-control @error('payment_collection_date') is-invalid @enderror "
                                                name="payment_collection_date"
                                                value="{{ old('payment_collection_date') ?? $visit_plan->payment_collection_date }}">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="payment_maturity_date">Payment Maturity Date</label>
                                            <input type="date"
                                                class="form-control @error('payment_maturity_date') is-invalid @enderror "
                                                name="payment_maturity_date"
                                                value="{{ old('payment_maturity_date') ?? $visit_plan->payment_maturity_date }}">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <label for="purpose_of_visit" class="form-label">Purpose of Visit</label>
                                    <textarea name="purpose_of_visit" id="purpose_of_visit" cols="30" rows="2"
                                        class="form-control @error('purpose_of_visit') is-invalid @enderror"> {{ $visit_plan->purpose_of_visit }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label for="achievement" class="form-label">Achievement</label>
                                    <textarea name="achievement" id="achievement" cols="30" rows="2"
                                        class="form-control @error('achievement') is-invalid @enderror">{{$visit->achievement}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-gray">Sales Part</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-10 m-auto">
                                        <div class="row">
                                            <div class="col-12 col-md-10">
                                                <select id="product" name="product"
                                                    class="form-control user-select select2-container product-select "
                                                    data-placeholder="Product Name / Model"
                                                    data-ajax-url="{{ route('global.productAllAjax') }}"
                                                    data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200"
                                                    style="">
                                                </select>
                                                <span class="text-danger" id="productError"></span>
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <a href="{{ route('admin.tempSalesItemAjax', $visit_plan) }}"
                                                    class="btn btn-sm btn-success addTempItem"><i
                                                        class="fas fa-plus"></i></a>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="showSaleItems py-2">
                                    @foreach ($sales_items as $sales_item)
                                        @include('employee.visitPlan.ajax.salesitem')
                                    @endforeach
                                </div>

                                <div class="col-12">
                                    <label for="sale_amount">Total Sale Amount</label>
                                    <input type="number" class="form-control @error('sale_amount') is-invalid @enderror "
                                        name="sale_amount" id="sale_amount" readonly
                                        value="{{ $visit_plan->total_sales_price() }}">
                                </div>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-gray">Collection Part</div>
                            <div class="card-body">
                                <div class="col-12">
                                    <label for="collection_details" class="form-label">Collection Details</label>
                                    <textarea name="collection_details" id="collection_details" cols="30" rows="2"
                                        class="form-control @error('collection_details') is-invalid @enderror"> {{$visit->collection_details}} </textarea>
                                </div>
                                <div class="col-12">
                                    <label for="collection_amount">Sale Amount</label>
                                    <input type="number"
                                        class="form-control @error('collection_amount') is-invalid @enderror "
                                        name="collection_amount" value="{{ old('collection_amount') ?? $visit->collection_amount }}">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="float-right">
                        <input type="submit" value="Update" class="btn btn-info">
                    </div>
                </div> --}}

                    {{-- Customer Modal Start --}}
                    {{-- @if ($customer = $visit_plan->customer)
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        <b>Customer:</b>{{ $visit_plan->customer ? $visit_plan->customer->customer_name : '' }}
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <ul class="list-group">
                                        <li class="list-group-item"><b>Name:</b> {{ $customer->customer_name }}</li>
                                        <li class="list-group-item"><b>Code:</b>{{ $customer->customer_code }}</li>
                                        <li class="list-group-item"><b>Ledger Balance:</b>{{ $customer->ledger_balance }}
                                        </li>
                                        <li class="list-group-item"><b>Employee Name:</b> {{ $customer->employee_name }}
                                        </li>
                                        <li class="list-group-item"><b>Company:</b>
                                            {{ $customer->company ? $customer->company->name : '' }} </li>
                                        <li class="list-group-item"><b>Address:</b> {{ $customer->client_address }},
                                            {{ $customer->area }}, {{ $customer->thana }}, {{ $customer->district }},
                                            {{ $customer->division }} </li>
                                        <li class="list-group-item"><b>Mobile:</b> {{ $customer->mobile }} </li>
                                        <li class="list-group-item"><b>Email:</b> {{ $customer->email }} </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif --}}
                    {{-- Customer Modal End --}}
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
            $('.employee-select').select2({
                theme: 'bootstrap4',
                closeOnSelect: true,
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


    <script>
        $(document).on('click', '.addTempItem', function(e) {
            e.preventDefault();
            var that = $(this);
            var productId = that.closest('.row').find('#product').val();
            var visit_id = that.attr('data_visit_id');

            if (productId == null) {
                that.closest('.row').find('#sale_productError').text('Select Product');
                return;
            }
            that.closest('.row').find('#sale_productError').text('');
            var url = that.attr('href');
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    product_id: productId,
                    visit_id: visit_id
                },
                success: function(res) {
                    if (res.success) {
                        $('.select2').val(null).trigger('change');
                        $("#sale_amount").val(res.sale_amount);
                        $('.showSaleItems').append(res.html);

                    }
                }
            })
        })
        $(document).on('click', '.closeItem', function(e) {
            e.preventDefault();
            var that = $(this);
            var url = that.attr('href');
            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    if (res.success) {
                        that.closest('.motherCard').remove();
                        $("#sale_amount").val(res.sale_amount);

                    }
                }
            })
        })

        function update_item(e, type) {
            var that = $(e);
            var url = that.closest('.motherCard').find('.update_url').val();
            if (type == 'quantity') {
                if (that.val() < 1) {
                    that.closest('.motherCard').find('.qtyError').text('Quantity Must be at last 1');
                    return;
                }
                that.closest('.motherCard').find('.qtyError').text('');
            }
            // var finalUrl = url + "?type=" + type;
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
                        if (res.sale_amount) {
                            $("#sale_amount").val(res.sale_amount);
                        }


                    }
                }
            })
        }
    </script>
    <script>
        $(document).on('change', '#sale_category', function() {
            var that = $(this);
            var category = that.val();
            var url = that.attr('data-url')
            $.ajax({
                url: url,
                method: "GET",
                data: {
                    category_id: category
                },
                success: function(res) {
                    if (res.success) {
                        $('.showSaleProduct').html(res.html)
                        $('.sale_product').select2({
                            theme: 'bootstrap4',
                            closeOnSelect: true,
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

                    }
                }
            })
        })
    </script>
    @include('employee.visitPlan.visit.part.edit.serviceScript')
@endpush
