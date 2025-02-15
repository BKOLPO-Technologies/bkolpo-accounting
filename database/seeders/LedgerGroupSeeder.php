<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LedgerGroup;

class LedgerGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ledgerGroups = [
            ['name' => 'Provisions'],
            ['name' => 'Liabilities'],
            ['name' => 'Income'],
            ['name' => 'Expense'],
            ['name' => 'Assets'],
        ];

        foreach ($ledgerGroups as $group) {
            LedgerGroup::updateOrCreate(
                ['group_name' => $group['name']], 
            );
        }
    }
}
