<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller{

    public function index(){
        return view("sections.sections",['sections'=>sections::all()]);
    }

    public function create(){
        //
    }

    public function store(Request $request){
        $vals = $request->validate([
            'section_name' => 'required|unique:sections|max:255',
            'description' => 'required'
        ],[
            'section_name.required' => 'يرجي ادخال اسم القسم',
            'section_name.unique' => ' اسم القسم مسجل مسبقا',
            'description.required' => ' يرجي ادخال وصف القسم',
        ]);

        $vals['created_by'] = auth()->user()->name;
        Sections::create($vals);
        return redirect('/sections')->with('success','تم اضافة القسم بنجاح');
    }

    public function show(sections $sections){
        //
    }

    public function edit(sections $sections){
        //
    }

    public function update(Request $request){
        $id = $request->id;
        $vals = $request->validate([
            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
            'description' => 'required',
        ],[
            'section_name.required' => 'يرجي ادخال اسم القسم',
            'section_name.unique' => ' اسم القسم مسجل مسبقا',
            'description.required' => ' يرجي ادخال وصف القسم',
        ]);
        $section = sections::find($id);
        $section->update($vals);
        return redirect('/sections')->with('success','تم تعديل القسم بنجاح');
    }

    public function destroy(Request $request){
        $id = $request->id;
        sections::find($id)->delete();
        return redirect('/sections')->with('success','تم حذف القسم بنجاح');
    }
}