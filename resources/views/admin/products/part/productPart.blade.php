<div class="table-responsive">
    <table class="table table-bordered table-sm text-nowrap">
        <thead>
            <tr>
                <th>Action</th>
                <th>Product Name </th>
                <th>Total Stock</th>
                <th>Category</th>
                <th>Unit Price </th>
                <th>Model </th>
                <th>Backup Time </th>
                <th>Warranty </th>
                <th>Brand </th>
                <th>Capacity </th>
                <th>type </th>
                <th>Origin </th>
                <th>Made In </th>
                <th>Short Description</th>
                {{-- <th>Terms and Condition </th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>
                        <div class="btn-group btn-sm">
                            @can('product-update')
                            <a href="{{ route('admin.product.edit', ['product' => $product, 'service_type' => request()->service_type]) }}"
                                class="btn btn-success btn-xs">Edit</a>
                            @endcan


                            <a href="{{route('admin.stockHistory',['product'=>$product])}}"
                                class="btn btn-warning btn-xs">Stock History</a>
                            {{-- <form action="{{ route('admin.product.destroy', $product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs"
                                    onclick="return confirm('Are You sure? You want to delete This Product')">Delete</button>
                            </form> --}}
                        </div>
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->category ? $product->category->name : '' }}</td>
                    <td>{{ $product->unit_price }}</td>
                    <td>{{ $product->model }}</td>
                    <td>{{ $product->backup_time }}</td>
                    <td>{{ $product->warranty }}</td>
                    <td>{{ $product->brand ? $product->brand->name : '' }}</td>
                    <td>{{ $product->capacity }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->origin }}</td>
                    <td>{{ $product->made_in }}</td>
                    <td>{{ Str::limit($product->short_description, 50, '...') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $products->appends(['type' => request()->service_type ?? '', 'q' => $q])->render() }}
