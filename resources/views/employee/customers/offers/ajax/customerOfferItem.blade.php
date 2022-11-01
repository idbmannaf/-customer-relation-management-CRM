<tr class="id-{{$customer_offer_item->id}} mother" data-url="{{route('employee.customerOfferItemUpdate',['offer'=>$customer_offer_item->customer_offer_id,'item'=>$customer_offer_item->id])}}">
    <td>
        <table class="table">
            <tr>
                <td>Name:</td>
                <td><input type="text" name="name" value="{{$customer_offer_item->product_name}}" oninput="changeOfferItem(this,'name')"></td>
            </tr>
            <tr>
                <td>VAH:</td>
                <td><input type="text" name="capacity" value="{{$customer_offer_item->product_capacity}}" oninput="changeOfferItem(this,'capacity')"></td>
            </tr>
            <tr>
                <td>Brand:</td>
                <td>
                    <select name="brand" id="id" onchange="changeOfferItem(this,'brand')">
                        @foreach ($brands as $brand)
                        <option {{$brand->name == $customer_offer_item->product_brand ? 'selected' : ''}} value="{{$brand->id}}">{{$brand->name}}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" name="brand" value="{{$customer_offer_item->product_brand}}" oninput="changeOfferItem(this,'brand')"> --}}

                </td>
            </tr>
            <tr>
                <td>Type:</td>
                <td><input type="text" name="type" value="{{$customer_offer_item->product_type}}" oninput="changeOfferItem(this,'type')"></td>
            </tr>
            <tr>
                <td>Origin:</td>
                <td><input type="text" name="origin" value="{{$customer_offer_item->product_origin}}" oninput="changeOfferItem(this,'origin')"></td>
            </tr>
            <tr>
                <td>Made In:</td>
                <td><input type="text" name="made_in" value="{{$customer_offer_item->product_made_in}}" oninput="changeOfferItem(this,'made_in')"></td>
            </tr>
            <tr>
                <td>Warranty:</td>
                <td><input type="text" name="warranty" value="{{$customer_offer_item->product_warranty}}" oninput="changeOfferItem(this,'warranty')"></td>
            </tr>

        </table>
    </td>
    <td><input type="number" type="any" min="0.00" name="quantity" oninput="changeOfferItem(this,'quantity')" value="{{$customer_offer_item->quantity}}"></td>
    <td><input type="number" type="any" min="0.00" name="unit_price" oninput="changeOfferItem(this,'unit_price')" value="{{$customer_offer_item->unit_price}}"></td>
    <td class="total_price">{{$customer_offer_item->total_price}}</td>
    <td>
        <a href="{{route('employee.customerOfferItemDelete',['offer'=>$customer_offer_item->customer_offer_id,'item'=>$customer_offer_item->id])}}" class="text-danger deleteItem"> <i class="fas fa-trash"></i></a>
    </td>
</tr>
