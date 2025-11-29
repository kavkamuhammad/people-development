<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'view-users', 'display_name' => 'View Users', 'description' => 'Can view user list'],
            ['name' => 'create-users', 'display_name' => 'Create Users', 'description' => 'Can create new users'],
            ['name' => 'edit-users', 'display_name' => 'Edit Users', 'description' => 'Can edit existing users'],
            ['name' => 'delete-users', 'display_name' => 'Delete Users', 'description' => 'Can delete users'],

            ['name' => 'view-roles', 'display_name' => 'View Roles', 'description' => 'Can view roles'],
            ['name' => 'create-roles', 'display_name' => 'Create Roles', 'description' => 'Can create roles'],
            ['name' => 'edit-roles', 'display_name' => 'Edit Roles', 'description' => 'Can edit roles'],
            ['name' => 'delete-roles', 'display_name' => 'Delete Roles', 'description' => 'Can delete roles'],

            ['name' => 'view-departments', 'display_name' => 'View Departments', 'description' => 'Can view departments'],
            ['name' => 'create-departments', 'display_name' => 'Create Departments', 'description' => 'Can create departments'],
            ['name' => 'edit-departments', 'display_name' => 'Edit Departments', 'description' => 'Can edit departments'],
            ['name' => 'delete-departments', 'display_name' => 'Delete Departments', 'description' => 'Can delete departments'],

            ['name' => 'view-job-levels', 'display_name' => 'View Job Levels', 'description' => 'Can view job levels'],
            ['name' => 'create-job-levels', 'display_name' => 'Create Job Levels', 'description' => 'Can create job levels'],
            ['name' => 'edit-job-levels', 'display_name' => 'Edit Job Levels', 'description' => 'Can edit job levels'],
            ['name' => 'delete-job-levels', 'display_name' => 'Delete Job Levels', 'description' => 'Can delete job levels'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission['name']],
                [
                    'display_name' => $permission['display_name'],
                    'description' => $permission['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
