<?php

namespace App\Http\Controllers;

use App\Http\Requests\product\StoreProductRequest;
use App\Http\Requests\product\UpdateProductRequest;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();

        return view('products.index', compact('sections','products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = $request->validated();
        Product::create($product);
        return back()->with('message', 'تم إضافة المنتج بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request)
    {
        $id = Section::where('name', $request->section_name)->first()->id;
        $product = Product::findOrFail($request->id);
        $data = $request->validated();
        $data['section_id'] = $id;
        $product->update($data);
        return back()->with('message', 'تم تعديل المنتج بنجاح');

        
        // $id = Section::where('name', $request->section_name)->first()->id;
        // $Products = Product::findOrFail($request->id);
        
        // $Products->update([
            //     'name' => $request->name,
            //     'description' => $request->description,
        //     'section_id' => $id,
        // ]);
        // return back()->with('message', 'تم تعديل المنتج بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Product::findOrFail($request->id)->delete();
        return redirect()->route('products.index')->with('message','تم حذف المنتج بنجاح');
    }
}
