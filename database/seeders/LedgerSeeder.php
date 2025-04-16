<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LedgerGroup;
use App\Models\LedgerSubGroup;
use App\Models\Ledger;
use App\Models\LedgerGroupSubgroupLedger;
use Illuminate\Support\Facades\Auth;

class LedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = Auth::id() ?? 1;

        // Define only Purchases and Sales types, plus Cash for Petty Cash
        $ledgerTypes = [
            'Petty Cash' => 'Cash',
            'Accounts Receivable' => 'Receivable',
            'Accounts Payable' => 'Payable',
            'Brac Bank A/C -2071145530001' => 'Bank',
            'Al Arafah Bank A/C -' => 'Bank',
            'Purchases' => 'Purchases',
            'Sales' => 'Sales',
        ];

        // Define Ledger Groups, Sub Groups, and Ledgers
        $groups = [
            'Asset' => [
                'Cash In Hand' => [
                    'ledgers' => ['Petty Cash'],
                    'type' => null, // Type will be assigned from $ledgerTypes
                ],
                'Current Asset' => [
                    'ledgers' => [
                        'Accounts Receivable',
                        'Office Equipment Furniture and Others',
                        'Brac Bank A/C -2071145530001',
                        'Al Arafah Bank A/C -',
                    ],
                    'type' => null,
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
                    'type' => null,
                ],
            ],
            'Income' => [
                'Sales Account' => [
                    'ledgers' => ['Discounts', 'Sales'], // Discounts will have no type
                    'type' => null,
                ],
                'Non-Operating Items' => [
                    'ledgers' => ['Interest Income', 'Interest Expenses'], // Discounts will have no type
                    'type' => null,
                ],
            ],
            'Expense' => [
                'Direct Expenses' => [
                    'ledgers' => ['Purchases', 'Salary'], // Salary will have no type
                    'type' => null,
                ],
                'Indirect Expense' => [
                    'ledgers' => [
                        'Cost of Billed Expenses', 'Cost of Shipping & Handling',
                        'Advertising', 'Employee Benefits', 'Accident Insurance', 'Entertainment',
                        'Office Expenses & Postage', 'Printing', 'Shipping & Couriers', 'Stationery',
                        'Other Expenses', 'Bank Fees', 'Business Insurance', 'Commissions',
                        'Repairs & Maintenance', 'Labour Wages', 'Salary', 'Legal Fees', 'Rent or Lease',
                        'Taxi & Parking', 'Uncategorized Expenses', 'Utilities', 'Mobile Phone Bill',
                        'Sales Taxes Paid',
                    ],
                    'type' => null,
                ],
                'Cost of Goods Sold' => [
                    'ledgers' => [
                        'Cost of Goods Sold'
                    ],
                    'type' => null,
                ],
                'Operating Expense' => [
                    'ledgers' => [
                        'Operating Expense'
                    ],
                    'type' => null,
                ],
            ],
        ];

        foreach ($groups as $groupName => $subGroups) {
            $group = LedgerGroup::create([
                'group_name' => $groupName,
                'created_by' => $userId,
            ]);

            foreach ($subGroups as $subGroupName => $data) {
                $subGroup = LedgerSubGroup::create([
                    'subgroup_name' => $subGroupName,
                    'ledger_group_id' => $group->id,
                    'created_by' => $userId,
                ]);

                foreach ($data['ledgers'] as $ledgerName) {
                    $type = $ledgerTypes[$ledgerName] ?? null; // Only assign specific types

                    $ledger = Ledger::create([
                        'name' => $ledgerName,
                        'opening_balance' => 0,
                        'ob_type' => null,
                        'type' => $type,
                        'created_by' => $userId,
                    ]);

                    LedgerGroupSubgroupLedger::create([
                        'group_id' => $group->id,
                        'sub_group_id' => $subGroup->id,
                        'ledger_id' => $ledger->id,
                    ]);
                }
            }
        }
    }
}
