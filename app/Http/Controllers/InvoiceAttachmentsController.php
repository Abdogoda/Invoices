<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice_attachments;
use App\Models\Invoices;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentsController extends Controller{

    public function index(){
        //
    }

    public function create(){
        
    }

    public function store(Request $request){
        $vals = $request->validate([
            'file_name' => 'mimes:pdf,jpeg,png,jpg',
        ],[
            'file_name.mimes' => 'صيغه المرفق يجب ان تكون pdf, jpeg, png, jpg'
        ]);
        if ($request->hasFile('file_name')) {
            $attachments = new Invoice_attachments();
            $attachments->file_name = $request->file('file_name')->store('Attachments', 'public');
            $attachments->invoice_number = $request->invoice_number;
            $attachments->Creatd_by = Auth::user()->name;
            $attachments->invoice_id = $request->invoice_id;
            $attachments->save();
        }
        return back()->with('success','تم اضافة المرفق بنجاح');
    }

    public function show(Invoice_attachments $invoice_attachments){
        //
    }

    public function edit(Invoice_attachments $invoice_attachments){
        //
    }

    public function update(Request $request, Invoice_attachments $invoice_attachments){
        //
    }

    public function destroy(Invoice_attachments $invoice_attachments){
        //
    }
}