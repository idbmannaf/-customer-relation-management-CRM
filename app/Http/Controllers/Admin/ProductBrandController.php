<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->type == 'spare_parts') {
            menuSubmenu('spare_parts','spare_partsBrands');
            $brands = ProductBrand::where('type','spare_parts')->latest()->paginate(50);
        }elseif($request->type == 'products'){
            menuSubmenu('products','productsBrands');
            $brands = ProductBrand::where('type','products')->latest()->paginate(50);
        }else{
            menuSubmenu('products','productsBrands');
            $brands = ProductBrand::latest()->paginate(50);
        }

        return view('admin.brands.brands',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required'
        ]);
        $brand = new ProductBrand;
        $brand->name = $request->name;
        $brand->type = $request->type;
        $brand->addedBy_id = Auth::id();
        $brand->save();
        return redirect()->back()->with('success','Brand Created Successuflly');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductBrand  $productBrand
     * @return \Illuminate\Http\Response
     */
    public function show(ProductBrand $productBrand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductBrand  $productBrand
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductBrand $productBrand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductBrand  $productBrand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductBrand $brand)
    {
        $request->validate([
            'name'=>'required'
        ]);

        $brand->name = $request->name;
        $brand->editedBy_id = Auth::id();
        $brand->save();
        return redirect()->back()->with('success','Brand Updated Successuflly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductBrand  $productBrand
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductBrand $brand)
    {
        if ($brand->hasProducts()) {
            return redirect()->back()->with('error','This brand have products so you are not able to delete this category');
           }

           $brand->delete();
           return redirect()->back()->with('success','brand Deleted Successuflly');
    }
}
