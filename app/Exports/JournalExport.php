<?php

namespace App\Exports;

use App\Models\Ledger;
use App\Models\Company;
use App\Models\Branch;
use App\Models\JournalVoucher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class JournalExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
       // Fetch all ledgers and format them dynamically
        $ledgers = Ledger::pluck('name', 'id')->toArray();
        $ledgerHeading = 'Ledger ID (' . collect($ledgers)
            ->map(fn($name, $id) => "$id=>$name")
            ->implode(', ') . ')';

        // Fetch all companies
        $companies = Company::pluck('name', 'id')->toArray();
        $companyHeading = 'Company ID (' . collect($companies)
            ->map(fn($name, $id) => "$id=>$name")
            ->implode(', ') . ')';

        // Fetch all branches
        $branches = Branch::pluck('name', 'id')->toArray();
        $branchHeading = 'Branch ID (' . collect($branches)
            ->map(fn($name, $id) => "$id=>$name")
            ->implode(', ') . ')';

        return [
            $companyHeading,  // Dynamic company heading
            $branchHeading,   // Dynamic branch heading
            'Date',
            $ledgerHeading,   // Dynamic ledger heading
            'Reference No',
            'Description',
            'Debit',
            'Credit',
        ];
    }
}
