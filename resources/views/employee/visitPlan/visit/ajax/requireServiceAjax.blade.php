

{{-- @include('employee.visitPlan.visit.ajax.requireServiceAjax') --}}
<tr class="serReqSerial">
    {{-- <td class="serialO">{{$serviceRequirement->id}}

    </td> --}}
    <td><input type="text" oninput="updateServiceRequire(this,'problem')" value="{{$serviceRequirement->problem}}" name="problem" class="problem form-control" placeholder="Problem"></td>
    <td><select name="spear_part_cat2" id="spear_part_cat2" class="spear_part_cat form-control">
        <option  value="">Select Type</option>
        <option {{$serviceRequirement->product_type == 'spare_parts' ? 'selected' : ''}} value="spare_parts">Spare Parts</option>
        <option {{$serviceRequirement->product_type == 'products' ? 'selected' : ''}} value="product">Product</option>
    </select></td>

    <td class="showProducts">
        <select id="service_product_{{$serviceRequirement->id}}" onchange="updateServiceRequire(this,'product_id')" name="product_2" class="form-control user-select service_product"
            data-placeholder="Product Name / Model"
            data-ajax-url="{{ route('employee.productAllAjax',['type'=>$serviceRequirement->product_type]) }}"
            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200">
            @if ($serviceRequirement->product)
            <option value="{{$serviceRequirement->product_id}}">{{$serviceRequirement->product->name}}</option>
            @endif
        </select>
        {{-- <input type="text" oninput="updateServiceRequire(this,'description')" value="{{$serviceRequirement->description}}" name="description" class="description">
 --}}

        <input type="hidden" class="updateServiceRequirement" value="{{route('employee.updateRequirementsOfBattAndSpearPartAjax', ['visit_plan' => $visit_plan,'item'=>$serviceRequirement->id])}}">
    </td>
    <td><input type="number" oninput="updateServiceRequire(this,'quantity')" value="{{$serviceRequirement->quantity}}" name="quantity" class="quantity form-control" placeholder="Quantity"></td>
    <td><input type="text" oninput="updateServiceRequire(this,'unit')" value="{{$serviceRequirement->unit}}" name="unit" class="unit form-control" placeholder="Unit"></td>
    <td><a href="{{ route('employee.deleteRequirementsOfBattAndSpearPartAjax', ['visit_plan' => $visit_plan,'item'=>$serviceRequirement->id]) }}" class="btn btn-sm btn-danger DeleteServiceReq"><i class="fas fa-trash"></i></a></td>
</tr>
@push('js')
<script>
    $(document).on('change', '.spear_part_cat2', function() {
        alert(1)
        var that = $(this);
        if (that.val()) {
            if (that.val() == 'spare_parts') {
                var html = ` <select id="service_product_{{$serviceRequirement->id}}" onchange="updateServiceRequired(this,'product_id')" name="product_2" class="form-control user-select service_product" data-55-url="{{route('employee.updateRequirementsOfBattAndSpearPartAjax', ['visit_plan' => $visit_plan,'item'=>$serviceRequirement->id])}}"
            data-placeholder="Product Name / Model"
            data-ajax-url="{{ route('employee.productAllAjax',['type'=>'spare_parts']) }}"
            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200">
            @if ($serviceRequirement->product)
            <option value="{{$serviceRequirement->product_id}}">{{$serviceRequirement->product->name}}</option>
            @endif
        </select>`;
            } else if (that.val() == 'product') {
                var html = ` <select id="service_product_{{$serviceRequirement->id}}" onchange="updateServiceRequired(this,'product_id')" name="product_2" class="form-control user-select service_product" data-55-url="{{route('employee.updateRequirementsOfBattAndSpearPartAjax', ['visit_plan' => $visit_plan,'item'=>$serviceRequirement->id])}}"
            data-placeholder="Product Name / Model"
            data-ajax-url="{{ route('employee.productAllAjax',['type'=>'products']) }}"
            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200">
            @if ($serviceRequirement->product)
            <option value="{{$serviceRequirement->product_id}}">{{$serviceRequirement->product->name}}</option>
            @endif
        </select>`;
            }

            that.closest('tr').find('.showProducts2').html(html);
            that.closest('tr').find('.showProducts3').html(`<p onchange="updateServiceRequire(this,'product_id')"></p>`);
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
    function updateServiceRequired(e, type) {

var that = $(e);
var value = that.val();
var url = that.attr('data-55-url');

$.ajax({
    url: url,
    type: "GET",
    data: {
        value: value,
        type: type
    },
    success: function(res) {

    }
})
}
</script>

@endpush
