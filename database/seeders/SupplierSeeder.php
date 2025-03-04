<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use Illuminate\Support\Facades\Hash;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'bd_supplier_name' => 'Bangladesh Textile Industries',
                'company' => 'Bangladesh Textile Industries',
                'phone' => '880-123-4567',
                'email' => 'supplier-bd@example.com',
                'address' => '123 Dhaka Road',
                'city' => 'Dhaka',
                'region' => 'Dhaka',
                'postbox' => '1212',
                'taxid' => 'SUP12345678',
                'password' => Hash::make('bangladeshpass'),
                'status' => 1,
            ],
            [
                'bd_supplier_name' => 'Dhaka Electronics Ltd.',
                'company' => 'Dhaka Electronics Ltd.',
                'phone' => '880-234-5678',
                'email' => 'supplier-dhaka@example.com',
                'address' => '456 Electronics Ave',
                'city' => 'Dhaka',
                'region' => 'Dhaka',
                'postbox' => '1213',
                'taxid' => 'SUP23456789',
                'password' => Hash::make('electronicspass'),
                'status' => 1,
            ],
            [
                'bd_supplier_name' => 'Chittagong Shipbuilding Co.',
                'company' => 'Chittagong Shipbuilding Co.',
                'phone' => '880-345-6789',
                'email' => 'supplier-chittagong@example.com',
                'address' => '789 Shipyard Road',
                'city' => 'Chittagong',
                'region' => 'Chittagong',
                'postbox' => '4222',
                'taxid' => 'SUP34567890',
                'password' => Hash::make('shipbuildingpass'),
                'status' => 1,
            ],
            [
                'bd_supplier_name' => 'Sylhet Agro Industries',
                'company' => 'Sylhet Agro Industries',
                'phone' => '880-456-7890',
                'email' => 'supplier-sylhet@example.com',
                'address' => '123 Agro Lane',
                'city' => 'Sylhet',
                'region' => 'Sylhet',
                'postbox' => '3131',
                'taxid' => 'SUP45678901',
                'password' => Hash::make('agroindustriespass'),
                'status' => 1,
            ],
        ];
        

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['email' => $supplier['email']], // Prevent duplicate suppliers
                $supplier
            );
        }
    }
}
