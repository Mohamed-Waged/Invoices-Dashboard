<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Exports\InvoicesExport;
use App\Models\invoices_detail;
use App\Notifications\AddInvoice;
use Illuminate\Support\Facades\DB;
use App\Models\invoices_attachment;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Insert Into Invoices Table
        invoice::create([
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

        // Insert Into Invoices_details Table
        $invoice_id = invoice::latest()->first()->id;
        invoices_detail::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        // Insert Into Invoices_attachments Table
        if ($request->hasFile('pic')) {
            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoices_attachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        // Add Notifications
        $user = User::get();
        $invoices = Invoice::latest()->first();
        Notification::send($user, new AddInvoice($invoices));

        return back()->with('message', 'تم إضافة الفاتورة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return view('invoices.status_update', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        $sections = Section::all();
        return view('invoices.edit_invoice', compact('sections', 'invoices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoices = Invoice::findOrFail($id);

        $this->validate($request, [
            'invoice_number' => 'unique:invoices,invoice_number,' . $id,
        ], [
            'invoice_number.unique' => 'رقم الفاتورة موجود بالفعل',
        ]);

        // Update Invoices Table
        $invoices->update([
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

        // Update Invoices_details Table
        invoices_detail::where('id_Invoice', $id)->update([
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        return redirect()->route('invoices.index')->with('message', 'تم تعديل الفاتورة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Delete Invoice From Invoices Table
        $id = $request->invoice_id;
        $invoice = Invoice::where('id', $id)->first();
        $Details = invoices_attachment::where('invoice_id', $id)->first();
        if (!empty($Details->invoice_number)) {
            Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        }
        $invoice->forceDelete();
        return redirect()->route('invoices.index')->with('message', 'تم حذف الفاتورة بنجاح');
    }

    // Archive Invoice From Invoices Table (SoftDelete)
    public function archive(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = Invoice::where('id', $id)->first();
        $invoice->delete();
        return redirect()->route('invoices.index')->with('message', 'تم أرشفة الفاتورة بنجاح');
    }

    // Return Product -> ( name , id ) ==> where id = $id
    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("name", "id");
        return json_encode($products);
    }

    // Update Status
    public function Status_Update(Request $request, $id)
    {
        $invoices = Invoice::findOrFail($id);
        if ($request->Status === 'مدفوعة') {
            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            invoices_detail::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            invoices_detail::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        return redirect()->route('invoices.index')->with('message', 'تم تعديل حالة الدفع بنجاح');
    }

    // Return Paid Invoices
    public function Invoice_Paid()
    {
        $invoices = Invoice::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    // Return UnPaid Invoices
    public function Invoice_UnPaid()
    {
        $invoices = Invoice::where('Value_Status', 2)->get();
        return view('invoices.invoices_unpaid', compact('invoices'));
    }

    // Return Partial Invoices
    public function Invoice_Partial()
    {
        $invoices = Invoice::where('Value_Status', 3)->get();
        return view('invoices.invoices_Partial', compact('invoices'));
    }

    // Print Invoice
    public function Print_invoice($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        return view('invoices.print_invoice', compact('invoice'));
    }

    // Export All Invoices To Excel
    public function export()
    {
        return Excel::download(new InvoicesExport, 'Invoices.xlsx');
    }

    // Mark All Notifications as Read
    public function MarkAsRead_all ()
    {
        $userUnreadNotification= auth()->user()->unreadNotifications;
        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }
    }

    // UnRead Notifications Count
    public function unreadNotifications_count()
    {
        return auth()->user()->unreadNotifications->count();
    }

     // UnRead Notifications
    public function unreadNotifications()
    {
        foreach (auth()->user()->unreadNotifications as $notification){
            return $notification->data['title'];
        }
    }
}