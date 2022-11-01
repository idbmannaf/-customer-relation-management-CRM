<tr>
    <td>{{ $item->product ? $item->product->name : '' }}
        <input type="hidden" name="product[]" value="{{ $item->product_id }}">
        <input type="hidden" name="url" class="url"
            value="{{ route('employee.updateReceiveProductForStockManage', $item) }}">
    </td>
    <td>
        {{ $item->product->product_type }}
    </td>
    <td>
        <input type="number" name="qty" id="qty" oninput="updateitem(this,'quantity')"
            value="{{ $item->quantity }}" class="form-control">
    </td>
    <td>
        <input type="text" name="details" id="details" oninput="updateitem(this,'details')"
            value="{{ $item->details }}" class="form-control">
    </td>
    <td>
        <input type="text" name="product_serial" id="product_serial" oninput="updateitem(this,'product_serial')"
            value="{{ $item->product_serial }}" class="form-control">
    </td>
    <td>
        <input type="text" name="invoice" id="invoice" oninput="updateitem(this,'invoice')"
            value="{{ $item->invoice }}" class="form-control">
    </td>
    <td>
        <input type="date" name="invoice_date" id="invoice_date" onchange="updateitem(this,'invoice_date')"
            value="{{ $item->invoice_date }}" class="form-control">
    </td>
    <td> <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="updateitem(this,'delete')">Delete</a></td>
</tr>
