<?php

namespace App\Imports;

use App\Models\LedgerGroup;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LedgerGroupImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        return new LedgerGroup([
            // Convert 'Ledger Group' to lowercase with underscore
            'group_name' => $row['group_name'],
        ]);
    }
}
