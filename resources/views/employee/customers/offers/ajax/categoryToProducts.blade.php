<div class="row">
    <div class="col-12 col-md-10">
        <div class="form-group">
            <label for="search">Select Product For Financial Proposal</label>
            <select id="product" name="product"
                class="form-control user-select select2-container employee-select select2"
                data-placeholder="Product Model/ Name"
                @if ($category_id == 0)
                data-ajax-url="{{ route('employee.productAllAjax') }}"
                @else
                data-ajax-url="{{ route('employee.productAllAjax',['category'=>$category_id]) }}"
                @endif
                data-ajax-cache="true"
                data-ajax-dataType="json" data-ajax-delay="200" style="">
            </select>
            <span class="text-danger productError"></span>
        </div>
    </div>
    <div class="col-12 col-md-2 pt-2 d-flex justify-content-start align-items-center">
        <a href=""
            data-url={{ route('employee.customerOfferItemAjax', ['customer' => $customer, 'offer' => $offer->id]) }}
            class="btn btn-info addBtn"><i class="fas fa-plus"></i></a>
    </div>
</div>
