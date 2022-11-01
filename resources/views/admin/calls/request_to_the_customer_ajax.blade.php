<tr>
    <td>{{ $item->id }}
        <input type="hidden" class="method"
            value="{{ route('employee.deleteProductToSendRequestToTheCustomerAjax', ['call' => $call, 'item' => $item]) }}">
        </td>
        <td><a href="" class="btn btn-sm btn-danger" onclick="change_quantity(this,'delete')"><i
                    class="fas fa-trash"></i></a></td>
    <td>{{ $item->product ? $item->product->name : '' }}</td>
    <td><input type="number" class="form-control" oninput="change_quantity(this,'quantity')" value="{{ $item->quantity }}">
    </td>
    <td>
        @if ($item->received)
            <span class="badge badge-success">Received</span>
            @elseif ($item->sent)
            <span class="badge badge-warning">Sent</span>
        @endif
    </td>
</tr>
