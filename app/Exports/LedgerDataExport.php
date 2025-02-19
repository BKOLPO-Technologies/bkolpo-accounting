<?php

namespace App\Exports;

use App\Models\Ledger;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LedgerDataExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ledger::all();
        //return Ledger::select("id", "name", "debit", "credit", "status")->get();
    }

    public function headings(): array
    {
        return ["ID", "Name", "Debit", "Credit", "Status"];
    }
}
