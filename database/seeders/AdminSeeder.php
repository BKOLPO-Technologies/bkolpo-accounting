<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add the admin user
        User::updateOrCreate(
            ['email' => 'admin@bkolpo.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@bkolpo.com',
                'password' => Hash::make('Admin@123#!'),
                'show_password' => 'Admin@123#!',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}
