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
                'name' => 'Global Supplies Ltd.',
                'company' => 'Global Supplies Ltd.',
                'phone' => '123-456-7890',
                'email' => 'supplier1@example.com',
                'address' => '123 Supplier Street',
                'city' => 'New York',
                'region' => 'NY',
                'country' => 'USA',
                'postbox' => '10001',
                'taxid' => 'SUP12345678',
                'password' => Hash::make('supplierpass123'),
                'status' => 1,
            ],
            [
                'name' => 'ABC Distribution',
                'company' => 'ABC Distribution',
                'phone' => '987-654-3210',
                'email' => 'supplier2@example.com',
                'address' => '456 Distribution Lane',
                'city' => 'Los Angeles',
                'region' => 'CA',
                'country' => 'USA',
                'postbox' => '90001',
                'taxid' => 'SUP98765432',
                'password' => Hash::make('securepass'),
                'status' => 1,
            ],
            [
                'name' => 'Premium Materials Inc.',
                'company' => 'Premium Materials Inc.',
                'phone' => '555-789-4561',
                'email' => 'supplier3@example.com',
                'address' => '789 Warehouse Road',
                'city' => 'Chicago',
                'region' => 'IL',
                'country' => 'USA',
                'postbox' => '60601',
                'taxid' => 'SUP56789012',
                'password' => Hash::make('strongpassword'),
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
