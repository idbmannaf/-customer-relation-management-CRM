<div class="row">
    <div class="col-12 col-md-10">
        <label for="search">Select Product</label>
        <select id="service_product" name="product" class="form-control user-select service_product"
            data-placeholder="Product Name / Model"
            @if ($category_id == 0) data-ajax-url="{{ route('employee.productAllAjax') }}"
            @else
            data-ajax-url="{{ route('employee.productAllAjax', ['category' => $category_id]) }}" @endif
            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200" style="">
        </select>
        <span class="text-danger" id="service_productError"></span>
    </div>
    <div class="col-12 col-md-2 pt-4 d-flex justify-content-start align-items-center">
        @if ($visit)
            <a href="{{ route('employee.ServiceProductAjax', $visit_plan) }}"
                class="btn btn-sm btn-success addServiceProduct" data_visit_id={{ $visit->id }}><i
                    class="fas fa-plus"></i></a>
        @else
            <a href="{{ route('employee.ServiceProductAjax', $visit_plan) }}"
                class="btn btn-sm btn-success addServiceProduct"><i class="fas fa-plus"></i></a>
        @endif
    </div>
</div>
