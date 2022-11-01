<div class="card">
    <div class="card-header bg-gray">Sales Part</div>
    <div class="card-body">



        <div class="row">
            <div class="col-12 col-md-4">
                <label for="category">Select Category</label>
                <select name="sale_product_type" id="sale_product_type" class="sale_product_type form-control">
                    <option value="">Select Type</option>
                    <option value="spare_parts">Spare Parts</option>
                    <option value="product">Product</option>
                </select>
            </div>
            <div class="col-12 col-md-8 showSaleProduct">
            </div>
        </div>

        <div class="showSaleItems py-2">
            @foreach ($sales_items as $sales_item)
                @include('employee.visitPlan.ajax.salesitem')
            @endforeach
        </div>

        <div class="col-12">
            <label for="sale_amount">Total Sale Amount</label>
            <input type="number" class="form-control @error('sale_amount') is-invalid @enderror " name="sale_amount"
                id="sale_amount" disabled readonly value="{{ $visit_plan->total_sales_price() }}">
        </div>

    </div>
</div>
@push('js')
    <script>
        $(document).on('change', '.sale_product_type', function() {
            var that = $(this);
            if (that.val()) {
                if (that.val() == 'spare_parts') {
                    var html = `<label for="service_product">Select Product</label><select id="${that.val()+Math.random()}" name="product_id"
class="form-control user-select service_product"
data-placeholder="Product Name / Model"
data-ajax-url="{{ route('employee.productAllAjax', ['type' => 'spare_parts']) }}"
data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200"
style="">
</select>`;
                } else if (that.val() == 'product') {
                    var html = `<label for="service_product">Select Product</label><select id="${that.val()+Math.random()}{{ rand(10000, 9999) }}" name="product_id"
class="form-control user-select service_product"
data-placeholder="Product Name / Model"
data-ajax-url="{{ route('employee.productAllAjax', ['type' => 'products']) }}"
data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200"
style="">
</select>`;
                }


                $('.showSaleProduct').html(html);
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
    </script>
@endpush
