<div class="card">
    <div class="card-header bg-gray">Sales Part</div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-10 m-auto">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label for="category">Select Category</label>
                        <select name="category" id="sale_category"
                            data-url="{{ route('employee.categoryToSaleProduct', ['visit_plan' => $visit_plan->id,'visit'=>$visit->id]) }}"
                            class="form-control select2">
                            <option value="" selected disabled>Select Category</option>
                            <option value="0">All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-8 showSaleProduct">
                    </div>
                </div>

            </div>

        </div>

        <div class="showSaleItems py-2">
            @foreach ($visit->sales_items as $sales_item)
                @include('employee.visitPlan.ajax.salesitem')
            @endforeach
        </div>

        <div class="col-12">
            <label for="sale_amount">Total Sale Amount</label>
            <input type="number"
                class="form-control @error('sale_amount') is-invalid @enderror "
                name="sale_amount" id="sale_amount" disabled readonly
                value="{{ $visit->total_sales_price() }}">

        </div>

    </div>
</div>
