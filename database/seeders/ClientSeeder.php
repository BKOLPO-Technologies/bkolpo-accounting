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
                'name' => 'Bkolpo Construction Ltd',
                'company' => 'Bkolpo Construction Ltd',
                'title' => 'Ahmed Ali',
                'designation' => 'Manager',
                'phone' => '01783465103',
                'email' => 'bkolpo@gmail.com',
                'address' => 'Tongi,Gazipur',
                'city' => 'Dhaka',
                'region' => 'NY',
                'country' => 'BD',
                'postbox' => '1001',
                'taxid' => 'TX12345678',
                'bin' => 'o98k7yu',
                'password' => Hash::make('12345678'),
                'status' => 1,
            ],
            [
                'name' => 'TechBkolpo Solutions.',
                'company' => 'TechBkolpo Solutions.',
                'title' => 'Sohanur Rahman',
                'designation' => 'Lead Executive',
                'phone' => '017834152654',
                'email' => 'techbkolpo@gmail.com',
                'address' => 'Tongi,Gazipur',
                'city' => 'Dhaka',
                'region' => 'CA',
                'country' => 'BD',
                'postbox' => '1002',
                'taxid' => 'TX98765432',
                'bin' => 'p94k7iu',
                'password' => Hash::make('12345678'),
                'status' => 1,
            ],
            [
                'name' => 'Innovative Solutions Inc.',
                'company' => 'Innovative Solutions Inc.',
                'title' => 'Ali Ahsan',
                'designation' => 'Manager',
                'phone' => '0178321452',
                'email' => 'innovative@gmail.com',
                'address' => 'Tongi,Gazipur',
                'city' => 'Dhaka',
                'region' => 'IL',
                'country' => 'BD',
                'postbox' => '1003',
                'taxid' => 'TX56789012',
                'bin' => '09uk4iu',
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
