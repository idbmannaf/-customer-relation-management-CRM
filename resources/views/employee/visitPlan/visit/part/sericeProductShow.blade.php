<tr>
    <td><input type="text" oninput="productDescriptionupdate(this,'device_name')" name="device_name" id="device_name" value="{{$product->device_name}}">
    <input type="hidden" class="productDescriptionupdate" value="{{route('employee.serviceProductUpdateAjax',['visit_plan'=>$visit_plan,'item'=>$product])}}">
    </td>
    <td><input type="text" oninput="productDescriptionupdate(this,'brand')" name="brand" id="brand" value="{{$product->brand}}"></td>
    <td><input type="text" oninput="productDescriptionupdate(this,'model_no')" name="model_no" id="model_no" value="{{$product->model_no}}"></td>
    <td><input type="text" oninput="productDescriptionupdate(this,'service_volt')" name="service_volt" id="service_volt" value="{{$product->service_volt}}"></td>
    <td><input type="text" oninput="productDescriptionupdate(this,'capacity')" name="capacity" id="capacity" value="{{$product->capacity}}"></td>
    <td><input type="text" oninput="productDescriptionupdate(this,'serial_no')" name="serial_no" id="serial_no" value="{{$product->serial_no}}"></td>
    <td><input type="text" oninput="productDescriptionupdate(this,'total_load')" name="total_load" id="total_load" value="{{$product->total_load}}"></td>
    <td>
        <a href="{{route('employee.serviceProductDeleteAjax',['visit_plan'=>$visit_plan,'item'=>$product])}}" class="text-danger serviceProductDeleteBtn" ><i class="fas fa-trash"></i></a>
    </td>
    {{-- <td><a href="{{route('employee.serviceProductDeleteAjax',['visit_plan'=>$visit_plan,'item'=>$product])}}" onclick="retun confirm('are you sure? You want to Delete This product?');" class="text-danger serviceProductDeleteBtn"> <i class="fas fa-trash"></i></a></td> --}}
</tr>
