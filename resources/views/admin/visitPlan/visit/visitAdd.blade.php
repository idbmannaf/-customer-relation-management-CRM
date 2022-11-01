@extends('admin.layouts.adminMaster')
@push('title')
    Admin Dashboard |Create Visit
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

        /* .product_description table, .product_description tr , .product_description th, .product_description td{
                                border: 1px solid;
                                border-collapse: collapse;
                            } */

        /* .aiz-switch input:empty {
                            height: 0;
                            width: 0;
                            overflow: hidden;
                            position: absolute;
                            opacity: 0;
                        }
                                .aiz-switch input:empty ~ span {
                            display: inline-block;
                            position: relative;
                            text-indent: 0;
                            cursor: pointer;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            -ms-user-select: none;
                            user-select: none;
                            line-height: 24px;
                            height: 21px;
                            width: 40px;
                            border-radius: 12px;
                        } */

        table.table.myt td {
            border: navajowhite;
            padding-top: 0px;
        }

        .showServiceRequirements input.form-control {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            outline: 1ch;
        }
    </style>
@endpush

@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Create Visits Of {{ $visit_plan->date_time }}
                @if ($visit_plan->customer_office_location_id)
                    <a href="javascript:void(0)" class="btn btn-danger">Already Checked</a>
                @endif
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            <form action="{{ route('admin.visit.add', $visit_plan) }}" method="POST">
                @csrf
                <input type="hidden" name="sibling_visit_id" value="{{ $sibling_visit_id }}">
                <div class="card">
                    <div class="col-12 pb-3">
                        <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat"
                            class="btn btn-info">Customer
                            Details</a>
                        @if ($visit_plan->call)
                            <a href="" class="btn btn-success  float-right" data-toggle="modal"
                                data-target="#vp-{{ $visit_plan->id }}" data-whatever="@fat">Service Call
                                Details</a>
                        @endif
                    </div>
                    @if (has_in_array($visit_plan->service_type, 'service'))
                        @include('admin.visitPlan.visit.part.service')
                    @endif
                    @if (has_in_array($visit_plan->service_type, 'sales'))
                        {{-- @include('admin.visitPlan.visit.part.sales') --}}
                    @endif
                    @if (has_in_array($visit_plan->service_type, 'collection'))
                        @include('admin.visitPlan.visit.part.collection')
                    @endif
                    @if (has_in_array($visit_plan->service_type, 'delivery'))
                        @include('admin.visitPlan.visit.part.delivery')
                    @endif

                    <div class="row">
                        <div class="remarks pt-2 col-12">
                            <div class="">
                                <label for="visit_attachment"> Visit Attachment:</label>
                                <input type="hidden" name="visit_plan_id" value="{{ $visit_plan->id }}">
                                <input type="file" multiple id="file" name="file[]">
                            </div>
                            <div class="images">
                                @foreach ($attachments as $attachment)
                                    <div class="attachment">
                                        <span class="serial">{{ $loop->index + 1 }} </span><a class="badge badge-success"
                                            href="{{ route('imagecache', ['template' => 'original', 'filename' => $attachment->name]) }}">
                                            {{ $attachment->name }}</a> <a
                                            href="{{ route('global.fileDelete', ['file' => $attachment->id]) }}"
                                            onclick="return confirm('Are you sure? You want to delete this file?');"
                                            class="badge badge-danger deleteAttachment"><i class="fas fa-times"></i></a>
                                    </div>
                                @endforeach
                            </div>

                        </div>

                        <div class="remarks pt-2 col-12">
                            <div class="">
                                <label for="remarks" class="d-flex align-items-center"> Remarks:</label>
                                <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="purpose_of_visit pt-2 col-12">
                            <div class="">
                                <label for="purpose_of_visit" class="d-flex align-items-center">Purpose Of Visit:</label>
                                <textarea name="purpose_of_visit" id="purpose_of_visit" class="form-control" cols="30" rows="3">
@if ($invoice)
0 TK  Paid from Invoice Id: {{ $invoice->id }} and Total Amount {{ $invoice->total_amount }}
@endif
</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 pt-2 m-auto">
                            <input type="submit" class="btn btn-info">
                        </div>
                    </div>


            </form>
        </div>
        @if ($visit_plan->call)
            @include('global.callModal')
        @endif
        @include('global.customerModal')
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script>
        // $(document).on('click', '.call_status', function() {
        //     var that = $(this);
        //     if (that.is(":checked")) {
        //         if (that.val() == 'done' || that.val() == 'reviewed') {
        //                 $(".show_service_charge").html(
        //                     `<input type="number" name="service_charge" placeholder="service charge" class="service_charge form-control">`
        //                 );

        //         } else {
        //             $(".show_service_charge").html('')
        //         }

        //     }

        // })
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
                    product_id: productId
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

    @include('admin.visitPlan.visit.part.serviceScript')
@endpush
