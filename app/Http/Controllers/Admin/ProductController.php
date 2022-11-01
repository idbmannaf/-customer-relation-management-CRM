<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Codec\OrderedTimeCodec;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $q = $request->q ?? '';
        if ($request->service_type == 'spare_parts') {
            menuSubmenu('spare_parts', 'all_spare_parts');

            $products = Product::with('brand', 'category')->where('product_type', 'spare_parts')->where(function ($query) use ($q) {
                if ($q) {
                    $query->where('name', 'like', "%" . $q . "%");
                    $query->orWhere('model', 'like', "%" . $q . "%");
                    $query->where('backup_time', 'like', "%" . $q . "%");
                }
            })
                ->latest()
                ->paginate(20);
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('admin.products.part.productPart', compact('products', 'q'))->render()
                ]);
            }
        } elseif ($request->service_type == 'products') {

            menuSubmenu('products', 'productsAll');
            $products = Product::with('brand', 'category')->where('product_type', 'products')->where(function ($query) use ($q) {
                if ($q) {
                    $query->where('name', 'like', "%" . $q . "%");
                    $query->orWhere('model', 'like', "%" . $q . "%");
                    $query->where('backup_time', 'like', "%" . $q . "%");
                }
            })
                ->latest()
                ->paginate(20);
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'html' => view('admin.products.part.productPart', compact('products', 'q'))->render()
                ]);
            }
        } else {
            menuSubmenu('products', 'productsAll');
            $products = Product::with('brand', 'category')->latest()->paginate(50);
        }

        return view('admin.products.products', compact('products', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $service_type = $request->service_type;

        $brands = ProductBrand::where('type', $service_type)->orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $categories = ProductCategory::where('type', $service_type)->orderBy('name')->get();
        return view('admin.products.addProduct', compact('brands', 'countries', 'categories', 'service_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->has('bulk_upload')) {
            $request->validate([
                'file' => 'required'
            ]);
            // if ($request->file->getClientOriginalExtension() != 'xlsx') {
            //     return redirect()->back()->with('error', "Only xlsx File Allowed");
            // }
            $upload = Excel::import(new ProductImport, request()->file('file'));
            if ($upload) {
                return back()->with('success', 'Product SuccessFully Added');
            }
        }

        $request->validate([
            'name' => 'required',
            'capacity' => 'required',
            'brand' => 'required',
            'model' => 'nullable',
            'backup_time' => 'required',
            'type' => 'required',
            'origin' => 'required',
            'made_in' => 'required',
            'unit_price' => 'required',
            'short_description' => 'required',
            'warranty' => 'required',
            'category' => 'required',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->capacity = $request->capacity;
        $product->brand_id = $request->brand;
        $product->model = $request->model;
        $product->backup_time = $request->backup_time;
        $product->type = $request->type;
        $product->origin = $request->origin;
        $product->made_in = $request->made_in;
        $product->warranty = $request->warranty;
        $product->unit_price = $request->unit_price;
        $product->short_description = $request->short_description;
        $product->category_id = $request->category;
        $product->product_type = $request->service_type;
        $product->stock = $request->stock ?? 0;
        $product->addedBy_id = Auth::id();
        $product->save();
        return redirect()->back()->with('success', 'product added successfully');
    }

    public function bulkUpload(Request $request)
    {
        # code...
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Product $product)
    {
        $service_type = $request->service_type;

        $brands = ProductBrand::where('type', $service_type)->orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $categories = ProductCategory::where('type', $service_type)->orderBy('name')->get();


        return view('admin.products.editProduct', compact('categories', 'brands', 'countries', 'product', 'categories', 'service_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'capacity' => 'nullable',
            'brand' => 'nullable',
            'model' => 'nullable',
            'backup_time' => 'nullable',
            'type' => 'nullable',
            'origin' => 'nullable',
            'made_in' => 'nullable',
            'unit_price' => 'nullable',
            'short_description' => 'nullable',
            'warranty' => 'nullable',
            'category' => 'required',
        ]);

        $product->name = $request->name;
        $product->capacity = $request->capacity;
        $product->brand_id = $request->brand;
        $product->model = $request->model;
        $product->backup_time = $request->backup_time;
        $product->type = $request->type;
        $product->origin = $request->origin;
        $product->made_in = $request->made_in;
        $product->warranty = $request->warranty;
        $product->unit_price = $request->unit_price;
        $product->short_description = $request->short_description;
        $product->category_id = $request->category;
        $product->stock = $request->stock ?? 0;
        $product->product_type = $request->service_type;
        $product->editedBy_id = Auth::id();
        $product->save();
        return redirect()->back()->with('success', 'Product Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return back();
        $product->delete();
        return redirect()->back()->with('success', 'Product Deleted successfully');
    }
    public function stockHistory(Request $request, Product $product)
    {
        $stock_histories = StockHistory::where('product_id',$product->id)->latest()->paginate(50);
        return view('admin.products.stockHistory', compact('stock_histories', 'product'));
    }
}
