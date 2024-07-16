<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\invoices_attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.archive', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = Invoice::withTrashed()->where('id', $id)->restore();
        return redirect()->route('archives.index')->with('message','تم الغاء أرشفة الفاتورة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = Invoice::withTrashed()->where('id',$id)->first();
        $Details = invoices_attachment::where('invoice_id', $id)->first();
        if (!empty($Details->invoice_number)) {
            Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        }
        $invoice->forceDelete();
        return redirect()->route('archives.index')->with('message','تم حذف الفاتورة بنجاح');
    }
}
