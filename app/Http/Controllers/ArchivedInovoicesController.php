<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;
use App\Models\ArchivedInovoices;
use App\Models\Invoice_attachments;
use Illuminate\Support\Facades\Storage;

class ArchivedInovoicesController extends Controller{
    public function index(){
        $invoices = Invoices::onlyTrashed()->get();
        return view('invoices.Archive_invoices',[
            'invoices' => $invoices
        ]);
    }

    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function show(ArchivedInovoices $archivedInovoices){
        //
    }

    public function edit(ArchivedInovoices $archivedInovoices){
        //
    }

    public function update(Request $request){
        $id = $request->invoice_id;
        Invoices::withTrashed()->where('id', $id)->restore();
        return redirect('/invoices')->with('restore_invoice',"message");
    }

    public function destroy(Request $request){
        $id = $request->id;
        $invoice = Invoices::withTrashed()->where('id',$id)->first();
        $details = Invoice_attachments::where('invoice_id',$id)->get();
            foreach($details as $detail){
                Storage::disk('public')->delete($detail->file_name);
            }
            $invoice->forceDelete();
            return redirect('/invoices')->with('delete_invoice','message');
    }
}