<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions data to be inserted
        $permissions = [
            ['name' => 'all.roles', 'guard_name' => 'web', 'group_name' => 'roles'],
            ['name' => 'add.roles', 'guard_name' => 'web', 'group_name' => 'roles'],
            ['name' => 'edit.roles', 'guard_name' => 'web', 'group_name' => 'roles'],
            ['name' => 'delete.roles', 'guard_name' => 'web', 'group_name' => 'roles'],
            ['name' => 'edit.rolesinpermission', 'guard_name' => 'web', 'group_name' => 'roles'],

            ['name' => 'all.users', 'guard_name' => 'web', 'group_name' => 'users'],
            ['name' => 'add.users', 'guard_name' => 'web', 'group_name' => 'users'],
            ['name' => 'edit.users', 'guard_name' => 'web', 'group_name' => 'users'],
            ['name' => 'delete.users', 'guard_name' => 'web', 'group_name' => 'users'],


            ['name' => 'menu.dashboard', 'guard_name' => 'web', 'group_name' => 'menu'],
            ['name' => 'menu.rolepermissions', 'guard_name' => 'web', 'group_name' => 'menu'],
            // ['name' => 'menu.leads', 'guard_name' => 'web', 'group_name' => 'menu'],

            ['name' => 'all.todo', 'guard_name' => 'web', 'group_name' => 'todo'],
            ['name' => 'add.todo', 'guard_name' => 'web', 'group_name' => 'todo'],
            ['name' => 'edit.todo', 'guard_name' => 'web', 'group_name' => 'todo'],
            ['name' => 'delete.todo', 'guard_name' => 'web', 'group_name' => 'todo'],

            ['name' => 'todo', 'guard_name' => 'web', 'group_name' => 'dashboard'],

            // ['name' => 'add.leads', 'guard_name' => 'web', 'group_name' => 'Leads'],
            // ['name' => 'all.leads', 'guard_name' => 'web', 'group_name' => 'Leads'],
            // ['name' => 'edit.leads', 'guard_name' => 'web', 'group_name' => 'Leads'],
            // ['name' => 'delete.leads', 'guard_name' => 'web', 'group_name' => 'Leads'],
            // ['name' => 'update.leads.status', 'guard_name' => 'web', 'group_name' => 'Leads'],
            // ['name' => 'edit_leads.due_date', 'guard_name' => 'web', 'group_name' => 'Leads'],
            // ['name' => 'export.excel.leads', 'guard_name' => 'web', 'group_name' => 'Leads'],
            // ['name' => 'export.pdf.leads', 'guard_name' => 'web', 'group_name' => 'Leads'],
            // ['name' => 'upload.image.leads', 'guard_name' => 'web', 'group_name' => 'Leads'],


            // ['name' => 'all.notification.templates', 'guard_name' => 'web', 'group_name' => 'notification'],
            // ['name' => 'add.notification.templates', 'guard_name' => 'web', 'group_name' => 'notification'],
            // ['name' => 'edit.notification.templates', 'guard_name' => 'web', 'group_name' => 'notification']
        ];

        // Extract names to keep
        $permissionNames = array_column($permissions, 'name');

        // Delete permissions not in the list
        Permission::whereNotIn('name', $permissionNames)->delete();

        // Insert each permission with auto-incrementing IDs
        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name'],
            ], [
                'group_name' => $permission['group_name'],
            ]);
        }

        // // Assign all permissions to Super Admin role
        // $superAdmin = Role::findOrFail(1);
        // if ($superAdmin) {
        //     $allPermissions = Permission::pluck('name')->toArray();
        //     $superAdmin->syncPermissions($allPermissions);  // Assign all in one go
        // }
    }
}
