<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'John Doe',
                'company' => 'Tech Solutions',
                'phone' => '123-456-7890',
                'email' => 'johndoe@example.com',
                'address' => '123 Main Street',
                'city' => 'New York',
                'region' => 'NY',
                'country' => 'USA',
                'postbox' => '10001',
                'taxid' => 'TX12345678',
                'password' => Hash::make('password123'),
                'status' => 1,
            ],
            [
                'name' => 'Jane Smith',
                'company' => 'Marketing Co.',
                'phone' => '987-654-3210',
                'email' => 'janesmith@example.com',
                'address' => '456 Market Street',
                'city' => 'Los Angeles',
                'region' => 'CA',
                'country' => 'USA',
                'postbox' => '90001',
                'taxid' => 'TX98765432',
                'password' => Hash::make('securepass'),
                'status' => 1,
            ],
            [
                'name' => 'David Johnson',
                'company' => 'Construction Ltd.',
                'phone' => '555-789-4561',
                'email' => 'davidjohnson@example.com',
                'address' => '789 Building Road',
                'city' => 'Chicago',
                'region' => 'IL',
                'country' => 'USA',
                'postbox' => '60601',
                'taxid' => 'TX56789012',
                'password' => Hash::make('strongpassword'),
                'status' => 1,
            ],
        ];

        foreach ($clients as $client) {
            Client::updateOrCreate(
                ['email' => $client['email']], // Ensure no duplicate clients by email
                $client
            );
        }
    }
}
