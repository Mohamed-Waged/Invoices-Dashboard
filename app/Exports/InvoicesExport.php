<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;

class InvoicesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Invoice::all();  //All Data In Invoices Table
        return Invoice::select('invoice_number','invoice_Date','Due_date','product','section_id','Status','note')->get();  
    }
}
