<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Products;
use App\Models\sections;
use Illuminate\Http\Request;

class InvoicesReportController extends Controller{
    
    public function index(){
        return view("reports.invoices_report");
    }

    public function search_invoices(Request $request){
        if($request->radio == 1){
            if($request->type && $request->start_at == '' && $request->end_at == ''){
                if($request->type == "الكل"){
                    $invoices = Invoices::all();
                }else{
                    $invoices = Invoices::select('*')->where('Status','=',$request->type)->get();
                }
            }else{
                $invoices = Invoices::select('*')->whereBetween('invoice_Date',[$request->start_at, $request->end_at])->get();
            }
            return view("reports.invoices_report", [
                'details' => $invoices,
                'type' => $request->type,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
            ]); 
        }else{
            if($request->invoice_number){
                $invoices = Invoices::select()->where("invoice_number" , "=" , $request->invoice_number)->get();
                return view("reports.invoices_report", [
                    'details' => $invoices,
                    'number' => $request->invoice_number
                ]);
            }
        }
    }
}