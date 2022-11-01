<div id="customerLocation">
    <label for="customer_office">Customer Address/Locaiton: <a href="" id="add-new-location" class="btn btn-sm btn-info"><i class="fas fa-plus"></i></a></label>
    <div class="customer_address_location">
        <select id="customer_office" required name="customer_address"
        class="form-control select2_customer_office select2-container step2-select select2 "
        data-placeholder="Select Customer Office Or Add New"
        data-ajax-url="{{ route('global.addOrEditCustomer', ['customer' => $customer->id]) }}" data-ajax-cache="true"
        data-ajax-dataType="json" data-ajax-delay="200" style="width: 100%;">

    </select>
    </div>
</div>

