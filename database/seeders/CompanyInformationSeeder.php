<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyInformation;

class CompanyInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyInformation::updateOrCreate(
            [
                'company_name' => 'Bkolpo Technologies',
            ],
            [
                'company_type' => 'Software Company',
                'logo' => '',
                'banner' => '',
                'invoice_header' => '',
                'country' => 'Bangladesh',
                'address' => 'Tokyo Tower(6th Floor) CeragAli, Tongi, Gazipur-1711, Bangladesh',
                'address_optional' => 'Tokyo Tower(6th Floor) CeragAli, Tongi, Gazipur-1711, Bangladesh',
                'email' => 'demo@gmail.com',
                'phone' => '+8801300295247',
                'city' => 'Gazipur',
                'state' => 'Bangladesh',
                'post_code' => '1000',
                'stock_warning' => '10',
                'currency_symbol' => '৳',
                'sms_api_code' => '',
                'sms_sender_id' => '',
                'sms_provider' => '',
                'sms_setting' => '',
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
