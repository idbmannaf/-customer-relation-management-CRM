@extends('admin.layouts.adminMaster')
@push('title')
     Admin Dashboard | Attendance Report
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
@endpush
@section('content')
    <div class="card shadow">
        <div class="card-header bg-info">
            <div class="card-title">Send Request To The Customer : @if($call->customer) {{$call->customer->customer_name}} ({{$call->customer->customer_code}}) @endif
            </div>
        </div>
        @include('alerts.alerts')
        <div class="card-body">
            @if (!$call->sent_product())
                <div class="row pb-2">
                    <div class="col-12 col-md-10 m-auto">
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <select name="spear_part_cat" id="spear_part_cat" class="spear_part_cat form-control">
                                    <option value="">Select Type</option>
                                    <option value="spare_parts">Spare Parts</option>
                                    <option value="product">Product</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6" id="showProducts">

                            </div>
                            <div class="col-12 col-md-2">
                                <input type="number" id="quantity" placeholder="Quantity" class="form-control">
                                <span class="text-danger quantityError"></span>
                            </div>
                            <div class="col-12 col-md-1">

                                <a href="{{ route('admin.addProductToSendRequestToTheCustomerAjax', $call) }}"
                                    class="btn btn-sm btn-success add"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="p-2 message text-danger"></div>


            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Action</td>
                            <td>Product</td>
                            <td>Quantity</td>
                            <td>Status</td>
                        </tr>
                    </thead>
                    <tbody class="showItem">
                        @foreach ($call->call_products as $item)
                            <tr>
                                <td>{{ $item->id }}
                                    <td>
                                        @if ($item->sent || $item->reveived)
                                        @else
                                            <a href=""
                                                class="btn btn-sm btn-danger" onclick="change_quantity(this,'delete')"><i class="fas fa-trash"></i></a>
                                        @endif


                                    </td>
                                <input type="hidden" class="method" value="{{ route('admin.deleteProductToSendRequestToTheCustomerAjax', ['call' => $call, 'item' => $item]) }}">
                                </td>
                                <td>{{ $item->product ? $item->product->name : '' }}</td>
                                <td>
                                    @if ($item->sent || $item->reveived)
                                    {{ $item->quantity }}
                                    @else
                                        <input type="number" class="form-control" oninput="change_quantity(this,'quantity')" value="{{ $item->quantity }}">
                                    @endif


                                </td>
                                <td>
                                    @if ($item->customer_received)
                                        <span class="badge badge-success">Customer Received</span>
                                    @elseif ($item->delivered)
                                        <span class="badge badge-warning">Delivered</span>
                                    @elseif ($item->received)
                                        <span class="badge badge-secondary">Received</span>
                                    @elseif ($item->sent)
                                        <span class="badge badge-danger">Sent</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (!$call->sent_product())
                <form action="{{ route('admin.sendRequestToTheCustomerPost', $call) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-3  m-auto">
                            <input type="submit" class="btn btn-success form-control" value="Submit">
                        </div>
                    </div>
                </form>
            @endif


        </div>

    </div>
@endsection



@push('js')
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script>
        $(document).on('change', '.spear_part_cat', function() {
            var that = $(this);
            if (that.val()) {
                if (that.val() == 'spare_parts') {
                    var html = `<select id="product" name="product_id"
    class="form-control user-select service_product"
    data-placeholder="Spare Parts Name / Model"
    data-ajax-url="{{ route('employee.productAllAjax', ['type' => 'spare_parts']) }}"
    data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200"
    style="">
</select><span class="productError"></span>`;
                } else if (that.val() == 'product') {
                    var html = `<select id="product" name="product_id"
    class="form-control user-select service_product"
    data-placeholder="Product Name / Model"
    data-ajax-url="{{ route('employee.productAllAjax', ['type' => 'products']) }}"
    data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200"
    style="">
</select><span class="productError"></span>`;
                }

                console.log($('#showProducts').html(html));
                $('.service_product').select2({
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
        });

        $(document).on('click', '.add', function(e) {
            e.preventDefault();

            var that = $(this);
            var productId = $('#product').val();
            var quantity = $('#quantity').val();
            if (productId == null) {
                $('.productError').text('Select Product/Spare Parts');
                return;
            }
            if (quantity < 1) {
                $('.quantityError').text('Select Product/Spare Parts');
                console.log('add2');
                return;
            }
            $('.productError').text('');
            $('.quantityError').text('');

            var url = that.attr('href');
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(res) {
                    if (res.success) {

                        $('.select2').val(null).trigger('change');
                        $('.showItem').append(res.html);

                    }
                }
            })
        })
        // $(document).on('click', '.delete', function(e) {
        //     e.preventDefault();
        //     var that = $(this);
        //     var url = that.attr('href');
        //     $.ajax({
        //         url: url,
        //         type: "GET",
        //         success: function(res) {
        //             if (res.success) {
        //                 that.closest('tr').remove();

        //             }
        //         }
        //     })
        // })
        function change_quantity(e,type){
           if(type =='delete'){
            e.preventDefault();
           }
            var that = $(e);
            var url = that.closest("tr").find('.method').val();
            var value = that.val();
            var quantity = 0;
            $.ajax({
                url: url,
                type: "GET",
                data:{type:type,value:value},
                success: function(res) {
                    if (res.success) {
                        $('#message').text('');
                        if (type == 'delete') {
                            that.closest('tr').remove();
                        }
                    }else{
                        $('#message').text(res.message);
                    }
                }
            })

        }
    </script>
@endpush
