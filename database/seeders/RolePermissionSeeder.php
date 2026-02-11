<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\SystemRole;
use App\Enums\SystemPermisson;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::firstOrCreate(['name' => SystemPermisson::VIEW_CONTENT->value]);
        Permission::firstOrCreate(['name' => SystemPermisson::UPDATE_CONTENT->value]);
        Permission::firstOrCreate(['name' => SystemPermisson::DELETE_CONTENT->value]);
        Permission::firstOrCreate(['name' => SystemPermisson::CREATE_CONTENT->value]);

        $admin = Role::firstOrCreate(['name' => SystemRole::ADMIN->value]);
        $admin->givePermissionTo(Permission::all());

        $editor = Role::firstOrCreate(['name' => SystemRole::EDITOR->value]);
        $editor->givePermissionTo([SystemPermisson::VIEW_CONTENT->value, SystemPermisson::UPDATE_CONTENT->value]);

        Role::firstOrCreate(['name' => SystemRole::USER->value]);
    }
}
