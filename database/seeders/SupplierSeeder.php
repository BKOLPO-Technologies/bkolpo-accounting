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
                'name' => 'Bangladesh Textile Industries',
                'company' => 'Bangladesh Textile Industries',
                'title' => 'John Doe',
                'designation' => 'Manager',
                'phone' => '880-123-4567',
                'email' => 'supplier-bd@example.com',
                'address' => '123 Dhaka Road',
                'zip' => '1212',
                'city' => 'Dhaka',
                'region' => 'Dhaka',
                'postbox' => '1212',
                'taxid' => 'SUP12345678',
                'bin' => '87yu',
                'password' => Hash::make('bangladeshpass'),
                'status' => 1,
            ],
            [
                'name' => 'Dhaka Electronics Ltd.',
                'company' => 'Dhaka Electronics Ltd.',
                'title' => 'William Walid',
                'designation' => 'Supervisor',
                'phone' => '880-234-5678',
                'email' => 'supplier-dhaka@example.com',
                'address' => '456 Electronics Ave',
                'zip' => '5124',
                'city' => 'Dhaka',
                'region' => 'Dhaka',
                'postbox' => '1213',
                'taxid' => 'SUP23456789',
                'bin' => 'hju65',
                'password' => Hash::make('electronicspass'),
                'status' => 1,
            ],
            [
                'name' => 'Chittagong Shipbuilding Co.',
                'company' => 'Chittagong Shipbuilding Co.',
                'title' => 'Md. Hasan',
                'designation' => 'Co - Ordinator',
                'phone' => '880-345-6789',
                'email' => 'supplier-chittagong@example.com',
                'address' => '789 Shipyard Road',
                'zip' => '6509',
                'city' => 'Chittagong',
                'region' => 'Chittagong',
                'postbox' => '4222',
                'taxid' => 'SUP34567890',
                'bin' => 'fd1234',
                'password' => Hash::make('shipbuildingpass'),
                'status' => 1,
            ],
            [
                'name' => 'Sylhet Agro Industries',
                'company' => 'Sylhet Agro Industries',
                'title' => 'Jaber Ahned',
                'designation' => 'Manager',
                'phone' => '880-456-7890',
                'email' => 'supplier-sylhet@example.com',
                'address' => '123 Agro Lane',
                'zip' => '1909',
                'city' => 'Sylhet',
                'region' => 'Sylhet',
                'postbox' => '3131',
                'taxid' => 'SUP45678901',
                'bin' => 'a123',
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
