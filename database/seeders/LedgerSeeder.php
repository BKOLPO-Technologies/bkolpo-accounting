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
            'Asset' => [
                'Cash In Hand' => ['Petty Cash'],
                'Current Asset' => [
                    'Accounts Receivable',
                    'Office Equipment Furniture and Others',
                    'Brac Bank A/C -2071145530001',
                    'Al Arafah Bank A/C -',
                ],
            ],
            'Liabilities' => [
                'Current Liabilities' => [
                    'Accounts Payable',
                    'Taxes Payable',
                    'Income Tax Payable',
                    'Unearned Revenue',
                    'Capital Account',
                ],
            ],
            'Income' => [
                'Sales Account' => [
                    'Discounts',
                    'Sales',
                ],
            ],
            'Expense' => [
                'Direct Expenses' => ['Purchases', 'Salary'],
                'Indirect Expense' => [
                    'Cost of Goods Sold',
                    'Cost of Billed Expenses',
                    'Cost of Shipping & Handling',
                    'Advertising',
                    'Employee Benefits',
                    'Accident Insurance',
                    'Entertainment',
                    'Office Expenses & Postage',
                    'Printing',
                    'Shipping & Couriers',
                    'Stationery',
                    'Other Expenses',
                    'Bank Fees',
                    'Business Insurance',
                    'Commissions',
                    'Repairs & Maintenance',
                    'Labour Wages',
                    'Salary',
                    'Legal Fees',
                    'Rent or Lease',
                    'Taxi & Parking',
                    'Uncategorized Expenses',
                    'Utilities',
                    'Mobile Phone Bill',
                    'Sales Taxes Paid',
                ],
                'Purchases Accounts' => [],
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
