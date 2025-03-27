<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminPermissions = collect([
            'template_view', 'template_create', 'template_edit', 'template_archive',
            'user_view', 'user_create', 'user_edit', 'user_archive',
        ])->map(function ($permission) {
            return [
                'name'       => $permission, 
                'guard_name' => 'api', 
                'created_at' => now()->toDateTimeString(), 
                'updated_at' => now()->toDateTimeString()
            ];
        });

        Permission::insert($adminPermissions->toArray());

        $role = Role::create(['name' => 'System Admin', 'guard_name' => 'api']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'User', 'guard_name' => 'api']);
    }
}
