<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Branch Permissions
            'branch-menu',
            'branch-list',
            'branch-create',
            'branch-edit',
            'branch-view',
            'branch-delete',
            // Company Permissions
            'company-menu',
            'company-list',
            'company-create',
            'company-edit',
            'company-view',
            'company-delete',
            // Cart Of Accounts
            'ledger-group-menu',
            'ledger-group-list',
            'ledger-group-create',
            'ledger-group-edit',
            'ledger-group-view',
            'ledger-group-delete',
            'ledger-menu',
            'ledger-list',
            'ledger-create',
            'ledger-edit',
            'ledger-view',
            'ledger-delete',
            // Journal Permissions
            'journal-menu',
            'journal-list',
            'journal-create',
            'journal-edit',
            'journal-view',
            'journal-delete',
            // Accounts Report Permissions
            'report-menu',
            'report-list',
            'trial-balnce-report',
            'balance-shit-report',
            // User Permissions
            'user-menu',
            'user-list',
            'user-create',
            'user-edit',
            'user-view',
            'user-delete',
            // Role Permissions
            'role-menu',
            'role-list',
            'role-create',
            'role-edit',
            'role-view',
            'role-delete',
            // Settings Permissions
            'dashboard-menu',
            'setting-menu',
            'setting-information',
            'setting-information-edit',
            'profile-view',
            'password-change',
        ];
        
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission], 
                [] 
            );
        }
        
    }
}
