<div class="card motherCard id{{$sales_item->id}}">
    <a href="{{route('employee.tempSalesItemDeleteAjax',['item'=>$sales_item,'visit_plan'=>$visit_plan])}}" class="text-danger croseBtn closeItem"><i class="fas fa-times fa-3x"></i></a>

    <input type="hidden" name="update_url" class="update_url" value="{{route('employee.tempSalesItemUpdateAjax',['item'=>$sales_item,'visit_plan'=>$visit_plan])}}">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" value="{{ $sales_item->product_name }}" class="form-control product_name" name="name" oninput="update_item(this,'name')" id="name">
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="warranty">Warranty</label>
                    <input type="text" value="{{ $sales_item->product_warranty }}" class="form-control product_warranty" oninput="update_item(this,'warranty')" name="warranty" id="warranty">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="capacity">Capacity</label>
                    <input type="text" value="{{ $sales_item->product_capacity }}" class="form-control" oninput="update_item(this,'capacity')" name="capacity" id="capacity">
                </div>

            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="backup_time">Backup Time</label>
                    <input type="text" value="{{ $sales_item->product_backup_time }}" class="form-control" name="backup_time" oninput="update_item(this,'backup_time')" id="backup_time">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" value="{{ $sales_item->product_quantity }}" class="form-control" name="quantity" id="quantity" oninput="update_item(this,'quantity')">
                    <span class="text-danger qtyError"></span>
                </div>

            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="unit_price">Unit Price</label>
                    <input type="text" oninput="update_item(this,'unit_price')"  value="{{ $sales_item->product_unit_price }}" class="form-control unit_price" name="unit_price" id="unit_price">
                </div>
            </div>
        </div>

    </div>
</div>
