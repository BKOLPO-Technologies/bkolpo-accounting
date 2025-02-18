<?php

namespace App\Exports;

use App\Models\Ledger;
use App\Models\LedgerGroup;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class LedgerExport implements FromCollection, WithHeadings
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
        // Fetch all ledger groups
        $ledgerGroups = LedgerGroup::pluck('group_name', 'id')->toArray();

        // Convert to format: "Group Name (1=>Asset, 2=>Expense)"
        $groupHeading = 'Group Name (' . collect($ledgerGroups)
            ->map(fn($name, $id) => "$id=>$name")
            ->implode(', ') . ')';

        return ['Ledger Name', $groupHeading, 'Opening Balance', 'Ending Balance'];
    }
}
