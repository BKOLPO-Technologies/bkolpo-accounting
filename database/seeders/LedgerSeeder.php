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
                'Cash In Hand' => [
                    'ledgers' => ['Petty Cash'],
                    'type' => 'Cash',  // Specify that this is a cash type
                ],
                'Current Asset' => [
                    'ledgers' => [
                        'Accounts Receivable',
                        'Office Equipment Furniture and Others',
                        'Brac Bank A/C -2071145530001',
                        'Al Arafah Bank A/C -',
                    ],
                    'type' => 'Bank',  // Default to bank type for accounts
                ],
            ],
            'Liabilities' => [
                'Current Liabilities' => [
                    'ledgers' => [
                        'Accounts Payable',
                        'Taxes Payable',
                        'Income Tax Payable',
                        'Unearned Revenue',
                        'Capital Account',
                    ],
                    'type' => null,  // No specific type (leave null for liabilities)
                ],
            ],
            'Income' => [
                'Sales Account' => [
                    'ledgers' => [
                        'Discounts',
                        'Sales',
                    ],
                    'type' => null,  // No specific type (income can be either)
                ],
            ],
            'Expense' => [
                'Direct Expenses' => [
                    'ledgers' => ['Purchases', 'Salary'],
                    'type' => null,  // No specific type (expenses don't need to specify type)
                ],
                'Indirect Expense' => [
                    'ledgers' => [
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
                    'type' => null,  // No specific type (expenses don't need to specify type)
                ],
                'Purchases Accounts' => [
                    'ledgers' => [],
                    'type' => null,  // No specific type (if no ledgers, it remains null)
                ],
            ],
        ];        
        
        // 🔸 Insert Groups, Sub Groups & Ledgers
        foreach ($groups as $groupName => $subGroups) {
            // 🔹 Insert Group
            $group = LedgerGroup::create([
                'group_name' => $groupName,
                'created_by' => $userId,
            ]);

            foreach ($subGroups as $subGroupName => $data) {
                // 🔹 Insert Sub Group
                $subGroup = LedgerSubGroup::create([
                    'subgroup_name'   => $subGroupName,
                    'ledger_group_id' => $group->id,
                    'created_by'      => $userId,
                ]);

                // 🔹 Get type for this sub-group
                $type = $data['type'] ?? null;

                foreach ($data['ledgers'] as $ledgerName) {
                    // Default values for opening balance and ob_type
                    $openingBalance = 0;
                    $obType = null;

                    // Assign the type for Cash and Bank ledgers
                    if ($type === 'Cash') {
                        $obType = 'debit'; // Typically debit for cash
                    } elseif ($type === 'Bank') {
                        $obType = 'debit'; // Typically debit for bank accounts
                    }

                    // 🔹 Insert Ledger
                    $ledger = Ledger::create([
                        'name'            => $ledgerName,
                        'opening_balance' => $openingBalance,
                        'ob_type'         => $obType,
                        'type'            => $type, // Assign the type from the array
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
