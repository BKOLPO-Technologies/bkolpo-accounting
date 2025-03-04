<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run()
    {
        // Using updateOrCreate to ensure that branches are created or updated
        Branch::updateOrCreate(
            ['name' => 'Dhaka Branch'], // Condition to find an existing branch by its name
            [
                'location' => 'Dhaka, Bangladesh',
                'description' => 'Main branch located in Dhaka.',
                'email' => 'dhaka@example.com',
                'password' => bcrypt('branchpassword123'),
                'show_password' => 'branchpassword123',
                'status' => 1, // Active
                'created_by' => 'admin',
                'updated_by' => 'admin',
            ]
        );

        Branch::updateOrCreate(
            ['name' => 'Chittagong Branch'],
            [
                'location' => 'Chittagong, Bangladesh',
                'description' => 'Branch located in the port city of Chittagong.',
                'email' => 'chittagong@example.com',
                'password' => bcrypt('branchpassword123'),
                'show_password' => 'branchpassword123',
                'status' => 1,
                'created_by' => 'admin',
                'updated_by' => 'admin',
            ]
        );

        Branch::updateOrCreate(
            ['name' => 'Sylhet Branch'],
            [
                'location' => 'Sylhet, Bangladesh',
                'description' => 'Branch located in the Sylhet region.',
                'email' => 'sylhet@example.com',
                'password' => bcrypt('branchpassword123'),
                'show_password' => 'branchpassword123',
                'status' => 1,
                'created_by' => 'admin',
                'updated_by' => 'admin',
            ]
        );
    }
}
