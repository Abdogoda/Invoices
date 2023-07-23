<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\sections;
use Illuminate\Http\Request;

class ProductsController extends Controller{

    public function index(){
        return view('products.products',[
            'products' => Products::all(),
            'sections' => sections::all()
        ]);
    }

    public function create(){
        //
    }

    public function store(Request $request){
        $vals = $request->validate([
            'product_name' => 'required|unique:products|max:255',
            'description' => 'required',
            'section_id' => 'required|integer',
        ],[
            'product_name.required' => 'يرجي ادخال اسم المنتج',
            'product_name.unique' => ' اسم المنتج مسجل مسبقا',
            'description.required' => ' يرجي ادخال وصف المنتج',
            'section_id.required' => ' يرجي ادخال اسم القسم',
        ]);
        Products::create($vals);
        return redirect('/products')->with('success','تم اضافة المنتج بنجاح');
    }

    public function show(Products $products){
        //
    }

    public function edit(Products $products){
        //
    }

    public function update(Request $request, Products $products){
        $id = $request->id;
        $vals = $request->validate([
            'product_name' => 'required|max:255|unique:products,product_name,'.$id,
            'description' => 'required',
            'section_id' => 'required|integer',
        ],[
            'product_name.required' => 'يرجي ادخال اسم المنتج',
            'product_name.unique' => ' اسم المنتج مسجل مسبقا',
            'description.required' => ' يرجي ادخال وصف المنتج',
            'section_id.required' => ' يرجي ادخال اسم القسم',
        ]);
        Products::find($id)->update($vals);
        return redirect('/products')->with('success','تم تعديل المنتج بنجاح');
    }

    public function destroy(Request $request){
        $id = $request->id;
        Products::find($id)->delete();
        return redirect('/products')->with('success','تم حذف المنتج بنجاح');
    }
}