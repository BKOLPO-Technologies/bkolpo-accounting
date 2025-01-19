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
                'password' => Hash::make('12345678'),
                'show_password' => '12345678',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Add Nazrul
        User::updateOrCreate(
            ['email' => 'nazrul@gmail.com'],
            [
                'name' => 'Nazrul',
                'email' => 'nazrul@gmail.com',
                'password' => Hash::make('12345678'),
                'show_password' => '12345678',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Add Asraful
        User::updateOrCreate(
            ['email' => 'asraful@gmail.com'],
            [
                'name' => 'Asraful',
                'email' => 'asraful@gmail.com',
                'password' => Hash::make('12345678'),
                'show_password' => '12345678',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Add Zim
        User::updateOrCreate(
            ['email' => 'zim@gmail.com'],
            [
                'name' => 'Zim',
                'email' => 'zim@gmail.com',
                'password' => Hash::make('12345678'),
                'show_password' => '12345678',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
    }
}
