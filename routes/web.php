<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchivedInovoicesController;
use App\Http\Controllers\CustomersReoprtController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use App\Models\ArchivedInovoices;
use App\Models\Invoice_attachments;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Invoices Pages Controller
Route::resource('invoices', InvoicesController::class)->middleware('auth');

// Products Pages Controller
Route::resource('Archive', ArchivedInovoicesController::class)->middleware('auth');

// Sections Pages Controller
Route::resource('sections', SectionsController::class)->middleware('auth');

// Show Products Of Section
Route::get('/section/{id}', [InvoicesController::class, 'getSectionProducts'])->middleware('auth');

// Products Pages Controller
Route::resource('products', ProductsController::class)->middleware('auth');

// invoice edit Page
Route::get('/edit_envoices/{id}', [InvoicesController::class,'edit'])->middleware('auth');

// status edit Page
Route::get('/status_show/{id}', [InvoicesController::class,'status_show'])->name('status_show')->middleware('auth');

// status update
Route::post('/Status_Update/{id}', [InvoicesController::class,'Status_Update'])->name('Status_Update')->middleware('auth');

// invoice details Page
Route::get('/invoiceDetails/{id}', [InvoiceDetailsController::class,'show'])->middleware('auth');

// delete file Page
Route::post('delete_file', [InvoiceDetailsController::class,'delete_file'])->name('delete_file')->middleware('auth');

// add file Page
Route::post('/InvoiceAttachments', [InvoiceAttachmentsController::class,'store'])->middleware('auth');

// print invoice Page
Route::get('Print_invoice/{id}', [InvoicesController::class,'Print_invoice'])->middleware('auth');

// invoice Pages
Route::get('Invoice_Paid', [InvoicesController::class,'Invoice_Paid'])->middleware('auth');
Route::get('Invoice_UnPaid', [InvoicesController::class,'Invoice_UnPaid'])->middleware('auth');
Route::get('Invoice_Partial', [InvoicesController::class,'Invoice_Partial'])->middleware('auth');
Route::get('invoice_export', [InvoicesController::class, 'export']);

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles',RoleController::class);
    Route::resource('users',UserController::class);
    });

Route::get('invoices_report',[InvoicesReportController::class, 'index']);
Route::post('search_invoices',[InvoicesReportController::class, 'search_invoices']);
Route::get('users_report',[CustomersReoprtController::class, 'index']);
Route::post('Search_customers',[CustomersReoprtController::class, 'Search_customers']);
Route::get('mark_all_read', [InvoicesController::class, 'mark_all_read']);

// Dashboard Page
Route::get('/{page}', [AdminController::class,'index']);