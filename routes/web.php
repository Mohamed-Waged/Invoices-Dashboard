<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicesArchiveController;
use App\Http\Controllers\InvoicesAttachmentController;
use App\Http\Controllers\InvoicesDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['auth'])->group(function(){
        Route::view('/','home');
        Route::resource('invoices', InvoiceController::class);
        Route::resource('sections', SectionController::class);
        Route::resource('products', ProductController::class);
        Route::resource('archives', InvoicesArchiveController::class);
        
        Route::get('section/{id}', [InvoiceController::class, 'getproducts']);
        Route::post('/Status_Update/{id}', [InvoiceController::class, 'Status_Update'])->name('Status_Update');
        
        Route::delete('archive_invoice',[InvoiceController::class, 'archive'])->name('archive_invoice');
        Route::get('Invoices_paid',[InvoiceController::class, 'Invoice_Paid']);
        Route::get('Invoices_unpaid',[InvoiceController::class, 'Invoice_UnPaid']);
        Route::get('Invoices_partial',[InvoiceController::class, 'Invoice_Partial']);
        // Print Invoice
        Route::get('Print_invoice/{id}',[InvoiceController::class,'Print_invoice'])->name('Print_invoice');
        // Export Inovice As Excel
        Route::get('export_invoices', [InvoiceController::class, 'export'])->name('export_invoices');
        
        // /////////
        Route::get('InvoicesDetails/{id}', [InvoicesDetailController::class, 'edit']);
        // File
        Route::get('View_file/{invoice_number}/{file_name}',[InvoicesDetailController::class, 'open_file']);
        Route::get('download/{invoice_number}/{file_name}',[InvoicesDetailController::class, 'get_file']);
        Route::post('delete_file', [InvoicesDetailController::class, 'destroy'])->name('delete_file');
        // /////////////
        Route::resource('InvoiceAttachments', InvoicesAttachmentController::class);
});


Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/{page}', [AdminController::class, 'index']);