<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LedgerGroup;
use App\Models\Ledger;
use App\Models\LedgerGroupDetail;
use Carbon\Carbon;

class LedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Ledger Groups
        $groups = [
            'Assets'       => 5,
            'Expense'      => 4,
            'Income'       => 3,
            'Liabilities'  => 2,
            'Provisions'   => 1,
        ];

        // Ledger Data with Group IDs
        $ledgers = [
            ['name' => 'Cash', 'debit' => 200000, 'group' => 'Assets'],
            ['name' => 'Bank', 'group' => 'Assets'],
            ['name' => 'Salary', 'group' => 'Expense'],
            ['name' => 'Travel Allowance', 'group' => 'Expense'],
            ['name' => 'Daily Allowance', 'group' => 'Expense'],
            ['name' => 'Office Maintenance', 'group' => 'Expense'],
            ['name' => 'Entertainment', 'group' => 'Expense'],
            ['name' => 'Repair & Maintenance', 'group' => 'Expense'],
            ['name' => 'CEO Withdraw', 'group' => 'Expense'],
            ['name' => 'Sales', 'group' => 'Income'],
            ['name' => 'Banking Payable', 'group' => 'Liabilities'],
            ['name' => 'GPH Ispat Ltd', 'group' => 'Liabilities'],
            ['name' => 'Salaries Payable', 'group' => 'Provisions'],
        ];

        foreach ($ledgers as $ledger) {
            // Ledger insert or update
            $ledgerModel = Ledger::updateOrCreate(
                ['name' => $ledger['name']],
                ['debit' => $ledger['debit'] ?? 0] 
            );

            // Insert into LedgerGroupDetail
            if (isset($ledger['group'])) {
                $groupId = $groups[$ledger['group']] ?? null;
                
                if ($groupId) {
                    LedgerGroupDetail::updateOrCreate(
                        ['ledger_id' => $ledgerModel->id, 'group_id' => $groupId],
                        []
                    );
                }
            }
        }
    }
}
