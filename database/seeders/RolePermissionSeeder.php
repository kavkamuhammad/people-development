<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = DB::table('roles')->get();
        $permissions = DB::table('permissions')->get();

        foreach ($roles as $role) {
            // Assign all permissions to admin
            if ($role->name === 'admin') {
                foreach ($permissions as $permission) {
                    DB::table('permission_role')->updateOrInsert(
                        [
                            'role_id' => $role->id,
                            'permission_id' => $permission->id
                        ]
                    );
                }
            }

            // Assign limited permissions to HR
            if ($role->name === 'hr') {
                $hrPermissions = $permissions->whereIn('name', ['view-users', 'create-users', 'edit-users']);
                foreach ($hrPermissions as $permission) {
                    DB::table('permission_role')->updateOrInsert(
                        [
                            'role_id' => $role->id,
                            'permission_id' => $permission->id
                        ]
                    );
                }
            }

            // Lainnya bisa ditambah sesuai kebutuhan
        }
    }
}
