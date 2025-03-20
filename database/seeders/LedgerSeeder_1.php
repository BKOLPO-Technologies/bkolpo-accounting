<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LedgerGroup;
use App\Models\LedgerSubGroup;
use App\Models\Ledger;
use App\Models\LedgerGroupSubgroupLedger;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class LedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = Auth::id() ?? 1;

        // 🔹 Ledger Groups & Sub Groups
        $groups = [
            'Assets' => [
                'Current Assets' => ['Cash', 'Bank', 'Accounts Receivable'],
                'Bank Accounts' => [],
                'Cash-in-Hand' => [],
                'Deposits (Asset)' => [],
                'Loans & Advances (Asset)' => [],
                'Stock-in-Hand' => [],
                'Sundry Debtors' => [],
                'Fixed Assets' => [],
                'Investments' => [],
            ],
            'Income' => [
                'Direct Incomes' => [],
                'Indirect Incomes' => [],
                'Sales Accounts' => ['Sales'],
            ],
            'Expenses' => [
                'Direct Expenses' => ['Purchases', 'Salary'],
                'Indirect Expenses' => [
                    'Travel Allowance',
                    'Daily Allowance',
                    'Office Maintenance',
                    'Entertainment',
                    'Repair & Maintenance',
                    'CEO Withdraw'
                ],
                'Purchases Accounts' => [],
            ],
            'Liabilities' => [
                'Capital Account' => [],
                'Current Liabilities' => ['Accounts Payable', 'Banking Payable', 'GPH Ispat Ltd'],
                'Duties & Taxes' => [],
                'Provisions' => ['Salaries Payable'],
                'Sundry Creditors' => [],
                'Loans (Liability)' => [],
                'Secured Loans' => [],
                'Unsecured Loans' => [],
                'Profit & Loss A/c' => [],
            ],
        ];
        

        // 🔸 Insert Groups, Sub Groups & Ledgers
        foreach ($groups as $groupName => $subGroups) {
            // 🔹 Insert Group
            $group = LedgerGroup::create([
                'group_name' => $groupName,
                'created_by' => $userId,
            ]);

            // 🔹 Insert Sub Groups
            foreach ($subGroups as $subGroupName => $ledgers) {
                $subGroup = LedgerSubGroup::create([
                    'subgroup_name'   => $subGroupName,
                    'ledger_group_id' => $group->id,
                    'created_by'      => $userId,
                ]);

                // 🔹 Insert Ledgers
                foreach ($ledgers as $ledgerName) {
                    // // Add opening_balance and ob_type to each ledger
                    // $openingBalance = 200000; // Default value
                    // $obType = 'debit'; // Default type
                    // if ($groupName == 'Income' || $groupName == 'Liabilities') {
                    //     $obType = 'credit'; // For Income and Liabilities, opening balance type will be credit
                    // }

                    // Default values (only for Cash & Bank)
                    $openingBalance = 0; 
                    $obType = null;  

                    // Only apply opening balance to Cash and Bank
                    if (in_array($ledgerName, ['Cash', 'Bank'])) {
                        $openingBalance = 0; // Default opening balance
                        $obType = 'debit'; // Default type for assets
                    }

                    if ($groupName == 'Income' || $groupName == 'Liabilities') {
                        $obType = 'credit'; // For Income and Liabilities, ob_type will be credit
                    }

                    $ledger = Ledger::create([
                        'name'            => $ledgerName,
                        'opening_balance' => $openingBalance,
                        'ob_type'         => $obType,
                        'created_by'      => $userId,
                    ]);

                    // 🔸 Insert into LedgerGroupSubgroupLedger table
                    LedgerGroupSubgroupLedger::create([
                        'group_id'     => $group->id,
                        'sub_group_id' => $subGroup->id,
                        'ledger_id'    => $ledger->id,
                    ]);
                }
            }
        }
    }
}
