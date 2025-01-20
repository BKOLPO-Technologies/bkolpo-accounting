<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        $user = User::updateOrCreate(
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

        // Define the roles
        $roles = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Inventory Manager'],
            ['id' => 3, 'name' => 'Sales Team'],
            ['id' => 4, 'name' => 'Sales Manager'],
            ['id' => 5, 'name' => 'Business Manager'],
            ['id' => 6, 'name' => 'Business Owner'],
            ['id' => 7, 'name' => 'Project Manager'],
            
        ];

        // Create or update roles
        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['id' => $roleData['id']],
                ['name' => $roleData['name']]
            );
        }

        // Assign permissions to the Admin role
        $adminRole = Role::findByName('Admin');
        $permissions = Permission::pluck('id','id')->all();
        $adminRole->syncPermissions($permissions);

        // Assign the Admin role to the user
        $user->assignRole($adminRole);
    }
}
