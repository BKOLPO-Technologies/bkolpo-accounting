<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            PermissionTableSeeder::class,
            AdminSeeder::class,
            CompanyInformationSeeder::class,
            LedgerSeeder::class,
            CategorySeeder::class,
            UnitSeeder::class,
            ProductSeeder::class,
            ClientSeeder::class,
            SupplierSeeder::class,
            BranchSeeder::class,
            CompanySeeder::class,
            ProjectsSeeder::class,
            // Add other seeders here 
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
