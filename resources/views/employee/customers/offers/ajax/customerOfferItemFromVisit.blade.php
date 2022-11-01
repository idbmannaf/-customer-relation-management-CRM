<tr>
    <td>
        <table class="table">
            <tr>
                <td>Name:</td>
                <td>{{$item->product ? $item->product->name : ''}}</td>
            </tr>
            <tr>
                <td>VAH:</td>
                <td>{{$item->product ? $item->product->capacity : ''}}</td>

            </tr>
            <tr>
                <td>Brand:</td>
                <td>{{$item->product ? ($item->product->brand ? $item->product->brand->name : '' ) : ''}}</td>
            </tr>
            <tr>
                <td>Type:</td>
                <td>{{$item->product ? $item->product->type : ''}}</td>
            </tr>
            <tr>
                <td>Origin:</td>
                <td>{{$item->product ? $item->product->origin : ''}}</td>
            </tr>
            <tr>
                <td>Made In:</td>
                <td>{{$item->product ? $item->product->made_in : ''}}</td>
            </tr>
            <tr>
                <td>Warranty:</td>
                <td>{{$item->product ? $item->product->warranty : ''}}</td>
            </tr>

        </table>
    </td>
    <td>{{$item->quantity}}</td>
    <td>{{$item->product ? $item->product->unit_price : 0.00}}</td>
    <td class="total_price">{{($item->product ? $item->product->unit_price : 0.00) * $item->quantity }}</td>
</tr>
