<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Products;
use App\Models\sections;
use App\Models\User;
use Illuminate\Http\Request;

class CustomersReoprtController extends Controller{
    public function index(){
        return view("reports.customers_report",[
            'sections' => sections::all(),
        ]);
    }

    public function Search_customers(Request $request){
        if($request->Section && $request->product && $request->start_at == '' && $request->end_at == ''){
            if($request->product == "الكل"){
                $details = Invoices::select('*')->where('section_id','=',$request->Section)->get();
            }else{
                $details = Invoices::select('*')->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
            }
        }else{
            if($request->product == "الكل"){
                $details = Invoices::select('*')->whereBetween('invoice_Date',[$request->start_at, $request->end_at])->where('section_id','=',$request->Section)->get();
            }else{
                $details = Invoices::select('*')->whereBetween('invoice_Date',[$request->start_at, $request->end_at])->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
            }
        }
        return view("reports.customers_report", [
            'details' => $details,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'sections' => sections::all(),
        ]); 
    }
}