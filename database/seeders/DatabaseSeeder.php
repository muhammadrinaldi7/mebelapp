<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);

        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@mebel.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        // Create Staff User
        $staff = User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@mebel.com',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole($staffRole);
        $this->call(Brand::class);
    }
}
