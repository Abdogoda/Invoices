<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoices;
use App\Models\Products;
use App\Models\sections;
use Illuminate\Http\Request;
use App\Exports\InvoicesExport;
use App\Models\Invoice_details;
use App\Notifications\AddInvoice;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice_attachments;
use App\Notifications\Add_invoice;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notification;

class InvoicesController extends Controller{

    public function index(){
        return view('invoices.invoices',[
            'invoices' => Invoices::all()
        ]);
    }

    public function show(Invoices $invoices){
        
    }

    public function create(){
        return view('invoices.add_invoice',[
            'sections' => sections::all(),
            'products' => Products::all()
            ]);
    }

    public function getSectionProducts($id){
        $states = DB::table('products')->where('section_id',$id)->get(['product_name','id']);
        return json_encode($states);
    }
    

    public function store(Request $request) {
        Invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);
        $invoice_id = Invoices::latest()->first()->id;
        Invoice_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);
        if ($request->hasFile('pic')) {
            $invoice_number = $request->invoice_number;
            $attachments = new Invoice_attachments();
            $attachments->file_name = $request->file('pic')->store('Attachments', 'public');
            $attachments->invoice_number = $invoice_number;
            $attachments->Creatd_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();
        }
        // send email & database notification
        $user = User::first();
        $user->notify(new AddInvoice($invoice_id)); 
        return back()->with('success', 'تم اضافة الفاتورة بنجاح');
    }

    public function edit($id){
        return view("invoices.edit_invoice",[
            'invoice' => Invoices::where('id',$id)->first(),
            'sections' => sections::all()
        ]);
    }

    public function status_show($id){
        return view("invoices.status_update",[
            'invoice' => Invoices::where('id',$id)->first()
        ]);
    }

    public function Status_Update($id, Request $request){
        $invoice = Invoices::findOrFail($id);
        $Value_Status = $invoice->Value_Status;
        if($request->Status == 'مدفوعة'){
            $Value_Status = 1;
        }elseif($request->Status == 'مدفوعة جزئيا'){
            $Value_Status = 3;
        }else{
            $Value_Status = 2;
        }
        $invoice->update([
            'Value_Status' => $Value_Status,
            'Status' => $request->Status,
            'Payment_Date' => $request->Payment_Date
        ]);
        Invoice_details::create([
            'id_Invoice' => $request->invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => $request->Status,
            'Value_Status' => $Value_Status,
            'note' => $request->note,
            'Payment_Date' => $request->Payment_Date,
            'user' => (Auth::user()->name),
        ]);
        return redirect('/invoices')->with('status_updated', 'تم تعديل حالة الفاتورة بنجاح');
    }

    public function update(Request $request){
        $invoice = Invoices::findOrFail($request->invoice_id);
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);
        return back()->with('success', 'تم تعديل الفاتورة بنجاح');
    }

    public function destroy(Request $request){
        $id = $request->id;
        $invoice = Invoices::where('id',$id)->first();
        if(isset($request->archive) && $request->archive == "archive"){
            $invoice->delete();
            return back()->with('archive_invoice','message');
        }else{
            $details = Invoice_attachments::where('invoice_id',$id)->get();
            foreach($details as $detail){
                Storage::disk('public')->delete($detail->file_name);
            }
            $invoice->forceDelete();
            return back()->with('delete_invoice','message');
        }
    }

    public function Invoice_Paid(){
        $invoices = Invoices::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid',compact('invoices'));
    }

    public function Invoice_unPaid(){
        $invoices = Invoices::where('Value_Status',2)->get();
        return view('invoices.invoices_unpaid',compact('invoices'));
    }

    public function Invoice_Partial(){
        $invoices = Invoices::where('Value_Status',3)->get();
        return view('invoices.invoices_Partial',compact('invoices'));
    }

    public function Print_invoice($id){
        $invoices = Invoices::findOrFail($id);
        return view('invoices.Print_invoice',compact('invoices'));
    }

    public function export() {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    public function mark_all_read() {
        $userUnreadNotifications = Auth::user()->unreadNotifications;
        if($userUnreadNotifications){
            $userUnreadNotifications->markAsRead();
            return back();
        }
    }
}