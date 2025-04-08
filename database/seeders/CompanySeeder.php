<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        // Update or Create the companies
        // Company::updateOrCreate(
        //     ['name' => 'Bkolpo Technologies'], // Look for an existing company by name
        //     [
        //         'branch_id' => 1, // Adjust branch_id as per your existing data
        //         'account_no' => 'BK1234567890',
        //         'initial_balance' => 5000.00,
        //         'description' => 'A leading technology company in Bangladesh.',
        //         'status' => 1, // Active
        //         'created_by' => 'admin',
        //         'updated_by' => 'admin',
        //     ]
        // );

        Company::updateOrCreate(
            ['name' => 'Bkolpo Construction Ltd.'],
            [
                'branch_id' => 2, // Adjust branch_id as per your existing data
                'account_no' => 'BK9876543210',
                'initial_balance' => 10000.00,
                'vat' => 10,
                'tax' => 5,
                'currency_symbol' =>'BDT',
                'fiscal_year' => 2425,
                'description' => 'A well-known construction company based in Bangladesh.',
                'status' => 1, // Active
                'created_by' => 'admin',
                'updated_by' => 'admin',
            ]
        );

        // Company::updateOrCreate(
        //     ['name' => 'Techno Builders Ltd.'],
        //     [
        //         'branch_id' => 3, // Adjust branch_id as per your existing data
        //         'account_no' => 'TB1230987654',
        //         'initial_balance' => 8000.00,
        //         'description' => 'A prominent construction company with a focus on infrastructure development.',
        //         'status' => 1, // Active
        //         'created_by' => 'admin',
        //         'updated_by' => 'admin',
        //     ]
        // );
    }
}
