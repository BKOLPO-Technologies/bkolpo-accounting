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
                'name' => 'Nazrul Islam',
                'company' => 'Bkolpo Technologies',
                'phone' => '01783465103',
                'email' => 'nsuzon02@gmail.com',
                'address' => 'Tongi,Gazipur',
                'city' => 'Dhaka',
                'region' => 'NY',
                'country' => 'BD',
                'postbox' => '1001',
                'taxid' => 'TX12345678',
                'password' => Hash::make('12345678'),
                'status' => 1,
            ],
            [
                'name' => 'Rifat Zahan Zim',
                'company' => 'Bkolpo Technologies.',
                'phone' => '017834152654',
                'email' => 'zim@gmail.com',
                'address' => 'Tongi,Gazipur',
                'city' => 'Dhaka',
                'region' => 'CA',
                'country' => 'BD',
                'postbox' => '1002',
                'taxid' => 'TX98765432',
                'password' => Hash::make('12345678'),
                'status' => 1,
            ],
            [
                'name' => 'Ashraful Islam',
                'company' => 'Bkolpo Technologies.',
                'phone' => '0178321452',
                'email' => 'ashraful@gmail.com',
                'address' => 'Tongi,Gazipur',
                'city' => 'Dhaka',
                'region' => 'IL',
                'country' => 'BD',
                'postbox' => '1003',
                'taxid' => 'TX56789012',
                'password' => Hash::make('12345678'),
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
