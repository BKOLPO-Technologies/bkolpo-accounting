<?php

namespace App\Exports;

use App\Models\JournalVoucher;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class JournalDataExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //return Ledger::all();
        return JournalVoucher::select("id", "transaction_code", "company_id", "branch_id", "transaction_date", "description", "status")->get();
    }

    public function headings(): array
    {
        return ["ID", "Transaction Code", "Company Id", "Branch Id", "Transaction Date", "Description", "Status"];
    }
}
