<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;
use App\Models\Invoice_details;
use App\Models\Invoice_attachments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

class InvoiceDetailsController extends Controller{

    public function index(){
        //
    }

    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function show($id){
        $invoice = Invoices::where('id', $id)->first();
        $details = Invoice_details::where('id_invoice', $id)->get();
        $attachments = Invoice_attachments::where('invoice_id', $id)->get();
        return view('invoices.details_invoice',[
            'invoice' => $invoice,
            'details' => $details,
            'attachments' => $attachments
        ]);
    }

    public function update(Request $request, Invoice_details $invoice_details){
        //
    }

    public function destroy(Invoice_details $invoice_details){
        //
    }

    public function download($file_name){
        return Storage::download($file_name);
    }

    public function delete_file(Request $request){
        $invoice = Invoice_attachments::findOrFail($request->id_file);
        $invoice->delete();
        Storage::disk('public')->delete($request->file_name);
        return back()->with('success','تم حذف المرفق بنجاح');
    }
}