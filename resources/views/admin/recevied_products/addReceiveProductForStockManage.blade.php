@extends('admin.layouts.adminMaster')
@push('title')
    Receive Products
@endpush
@push('css')
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
@endpush
@section('content')
    <div class="card">
        <div class="card-header bg-success">Add New Received Product/Spare Parts </div>
        <div class="card-body">
            @include('alerts.alerts')
            <form action="{{route('admin.storeReceiveProductForStockManage')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-2 form-group">
                        <select name="spear_part_cat" id="spear_part_cat" class="spear_part_cat form-control">
                            <option value="">Select Type</option>
                            <option value="spare_parts">Spare Parts</option>
                            <option value="product">Product</option>
                        </select>
                        <span class="text-danger catErr"></span>
                    </div>
                    <div class="col-12 col-md-4 showProducts from-group">
                    </div>
                    <div class="col-12 col-md-2 from-group">
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="number" name="quantity" class="quantity form-control" placeholder="Quantity"> <span
                            class="text-danger qtyErr"></span>
                    </div>
                    <div class="col-12 col-md-2">
                        <a href="{{ route('admin.tempAddReceiveProductForStockManage') }}"
                            class="btn btn-sm btn-success addTemp"><i class="fas fa-plus"></i></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Problem</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="productsInTbody">
                            @foreach ($temp_products as $item)
                                <tr>
                                    <td>{{ $item->product ? $item->product->name : '' }}
                                        <input type="hidden" name="product[]" value="{{ $item->product_id }}">
                                        <input type="hidden" name="url" class="url" value="{{ route('admin.updateReceiveProductForStockManage',$item) }}">
                                    </td>
                                    <td>
                                        {{ $item->product->product_type }}
                                    </td>
                                    <td>
                                        <input type="number" name="qty" id="qty" oninput="updateitem(this,'quantity')" value="{{ $item->quantity }}"
                                            class="form-conttrol">
                                    </td>
                                    <td>
                                        <input type="text" name="details" id="details" oninput="updateitem(this,'details')" value="{{ $item->details }}"
                                        class="form-conttrol">
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="updateitem(this,'delete')">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-12 col-md-2 m-auto">
                        <input type="submit" class="form-control btn btn-success" value="Product Received">
                    </div>
                </div>
            </form>
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
                    var html = `<select id="${that.val()+Math.random()}" name="product_id"
class="form-control user-select service_product"
data-placeholder="Product Name / Model"
data-ajax-url="{{ route('global.productAllAjax', ['type' => 'spare_parts']) }}"
data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200"
style="">
</select> </br> <span class="text-danger spare_partsErr"></span>`;
                } else if (that.val() == 'product') {
                    var html = `<select id="${that.val()+Math.random()}{{ rand(10000, 9999) }}" name="product_id"
class="form-control user-select service_product"
data-placeholder="Product Name / Model"
data-ajax-url="{{ route('global.productAllAjax', ['type' => 'products']) }}"
data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200"
style="">
</select></br> <span class="text-danger productsErr"></span>`;
                }


                $('.showProducts').html(html);
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
        })
        $('.addTemp').click(function(e) {
            e.preventDefault();
            var type = $('#spear_part_cat').val();
            var product = $('.service_product').val();
            var quantity = $('.quantity').val();
            var Err = type + "Err";
            var url = $(this).attr('href');
            if (!type) {
                $('.catErr').text('Select Product Type');
                return;
            }
            if (!product) {
                $('.' + Err).text('Select type Product');
                return;
            }
            if (!quantity) {

                $('.qtyErr').text('Quantity Required');
                return;
            }
            $('.catErr').text(' ');
            $('.' + Err).text(' ');
            $('.qtyErr').text(' ');

            $.ajax({
                url: url,
                method: "GET",
                data: {
                    type: type,
                    product: product,
                    quantity: quantity
                },
                success: function(res) {
                    if (res.success) {
                        $('.productsInTbody').prepend(res.html);

                    } else {

                    }
                    $('.' + Err).text(res.message);
                }
            })
        })

        function updateitem(e, type) {
            var that = $(e);
            url = that.closest('tr').find('.url').val();
            if (type == 'quantity') {
                quantity = that.val();
                if (quantity < 1) {
                    that.val(1);
                    that.trigger('oninput');
                    return;
                }
            } else {
                quantity = 0;
            }
            var details = null;
            if (type == 'details') {
                details = that.val();
            }

            $.ajax({
                url: url,
                method: "GET",
                data: {
                    quantity: quantity,
                    type: type,
                    details:details
                },
                success: function(res) {
                    if (res.type == 'delete') {
                        that.closest('tr').remove();
                    }
                }
            })
        }
    </script>
@endpush
