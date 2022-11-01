<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if ($request->type == 'spare_parts') {
            menuSubmenu('spare_parts','spare_partsCategories');
            $categories = ProductCategory::where('type','spare_parts')->latest()->paginate(50);
        }elseif($request->type == 'products'){
            menuSubmenu('products','productsCategories');
            $categories = ProductCategory::where('type','products')->latest()->paginate(50);
        }else{
            menuSubmenu('products','productsCategories');
            $categories = ProductCategory::latest()->paginate(50);
        }

        return view('admin.categories.category',compact('categories'));
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
       $product_category = new ProductCategory;
       $product_category->name = $request->name;
       $product_category->type = $request->type;
       $product_category->addedBy_id = Auth::id();
       $product_category->save();
       return redirect()->back()->with('success','Category Created Successuflly');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategory $category)
    {
        $request->validate([
            'name'=>'required'
        ]);

        $category->name = $request->name;
        $category->editedBy_id = Auth::id();
        $category->save();
        return redirect()->back()->with('success','Category Updated Successuflly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $category)
    {
       if ($category->hasProducts()) {
        return redirect()->back()->with('error','This Category have products so you are not able to delete this categoruy');
       }

       $category->delete();
       return redirect()->back()->with('success','Category Deleted Successuflly');
    }
}
