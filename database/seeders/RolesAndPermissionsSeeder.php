<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            // Dashboard
            'view dashboard',
            // Medicines
            'view medicines', 'create medicines', 'edit medicines', 'delete medicines',
            // Categories
            'view categories', 'create categories', 'edit categories', 'delete categories',
            // Suppliers
            'view suppliers', 'create suppliers', 'edit suppliers', 'delete suppliers',
            // Customers
            'view customers', 'create customers', 'edit customers', 'delete customers',
            // Purchases
            'view purchases', 'create purchases',
            // Sales / POS
            'view sales', 'create sales', 'access pos',
            // Expenses
            'view expenses', 'create expenses', 'edit expenses', 'delete expenses',
            // Reports
            'view reports',
            // Settings
            'view settings', 'edit settings',
            // Users
            'view users', 'create users', 'edit users', 'delete users',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Define roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'view dashboard',
            'view medicines', 'create medicines', 'edit medicines',
            'view categories', 'create categories', 'edit categories',
            'view suppliers', 'create suppliers', 'edit suppliers',
            'view customers', 'create customers', 'edit customers',
            'view purchases', 'create purchases',
            'view sales', 'create sales', 'access pos',
            'view expenses', 'create expenses', 'edit expenses',
            'view reports',
        ]);

        $pharmacist = Role::firstOrCreate(['name' => 'Pharmacist']);
        $pharmacist->syncPermissions([
            'view dashboard',
            'view medicines', 'edit medicines',
            'view categories',
            'view suppliers', 'view purchases', 'create purchases',
            'view sales', 'create sales', 'access pos',
            'view reports',
        ]);

        $cashier = Role::firstOrCreate(['name' => 'Cashier']);
        $cashier->syncPermissions([
            'access pos',
            'create sales',
        ]);

        $storeManager = Role::firstOrCreate(['name' => 'Store Manager']);
        $storeManager->syncPermissions([
            'view dashboard',
            'view medicines', 'create medicines', 'edit medicines',
            'view categories',
            'view suppliers', 'create suppliers',
            'view purchases', 'create purchases',
            'view expenses', 'create expenses',
            'view reports',
        ]);

        // Create default users
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@pharmacy.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
                'phone'    => '+92-300-1234567',
                'status'   => 'active',
            ]
        );
        $adminUser->assignRole('Super Admin');

        $pharmacistUser = User::firstOrCreate(
            ['email' => 'pharmacist@pharmacy.com'],
            [
                'name'     => 'Dr. Ahmed Pharmacist',
                'password' => Hash::make('password'),
                'phone'    => '+92-301-2345678',
                'status'   => 'active',
            ]
        );
        $pharmacistUser->assignRole('Pharmacist');

        $cashierUser = User::firstOrCreate(
            ['email' => 'cashier@pharmacy.com'],
            [
                'name'     => 'Ali Cashier',
                'password' => Hash::make('password'),
                'phone'    => '+92-302-3456789',
                'status'   => 'active',
            ]
        );
        $cashierUser->assignRole('Cashier');

        $managerUser = User::firstOrCreate(
            ['email' => 'manager@pharmacy.com'],
            [
                'name'     => 'Usman Manager',
                'password' => Hash::make('password'),
                'phone'    => '+92-303-4567890',
                'status'   => 'active',
            ]
        );
        $managerUser->assignRole('Store Manager');

        $this->command->info('✅ Roles, Permissions, and Users seeded successfully.');
    }
}
