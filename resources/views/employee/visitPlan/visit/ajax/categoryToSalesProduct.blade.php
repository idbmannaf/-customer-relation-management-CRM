<div class="row">
    <div class="col-12 col-md-10">
        <label for="search">Select Product</label>
        <select id="product" name="product" class="form-control user-select sale_product"
            data-placeholder="Product Name / Model"
            @if ($category_id == 0) data-ajax-url="{{ route('employee.productAllAjax') }}"
            @else
            data-ajax-url="{{ route('employee.productAllAjax', ['category' => $category_id]) }}" @endif
            data-ajax-cache="true" data-ajax-dataType="json" data-ajax-delay="200" style="">
        </select>
        <span class="text-danger" id="sale_productError"></span>
    </div>
    <div class="col-12 col-md-2 pt-4 d-flex justify-content-start align-items-center">

        @if ($visit)
            <a href="{{ route('employee.tempSalesItemAjax', $visit_plan) }}"
                            class="btn btn-sm btn-success addTempItem" data_visit_id ='{{$visit->id}}'><i
                                class="fas fa-plus"></i></a>
        @else
           <a href="{{ route('employee.tempSalesItemAjax', $visit_plan) }}"
        class="btn btn-sm btn-success addTempItem"><i
            class="fas fa-plus"></i></a>
        @endif
    </div>
</div>
