<?php

namespace App\Exports;

use App\Models\LedgerGroup;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ledgerGroupDataExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return ledgerGroup::all();
    // }
    public function collection()
    {
        //return Ledger::all();
        return LedgerGroup::select("id", "group_name", "status", "created_by", "updated_by")->get();
    }

    public function headings(): array
    {
        return ["ID", "Group Name", "Status", "Created By", "Updated By"];
    }
}
