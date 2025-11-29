<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------
        // Permissions
        // -------------------------
        $permissions = [
            // Users
            ['name' => 'view-users', 'display_name' => 'View Users', 'description' => 'Can view users'],
            ['name' => 'create-users', 'display_name' => 'Create Users', 'description' => 'Can create users'],
            ['name' => 'edit-users', 'display_name' => 'Edit Users', 'description' => 'Can edit users'],
            ['name' => 'delete-users', 'display_name' => 'Delete Users', 'description' => 'Can delete users'],

            // Roles
            ['name' => 'view-roles', 'display_name' => 'View Roles', 'description' => 'Can view roles'],
            ['name' => 'create-roles', 'display_name' => 'Create Roles', 'description' => 'Can create roles'],
            ['name' => 'edit-roles', 'display_name' => 'Edit Roles', 'description' => 'Can edit roles'],
            ['name' => 'delete-roles', 'display_name' => 'Delete Roles', 'description' => 'Can delete roles'],

            // Departments
            ['name' => 'view-departments', 'display_name' => 'View Departments', 'description' => 'Can view departments'],
            ['name' => 'create-departments', 'display_name' => 'Create Departments', 'description' => 'Can create departments'],
            ['name' => 'edit-departments', 'display_name' => 'Edit Departments', 'description' => 'Can edit departments'],
            ['name' => 'delete-departments', 'display_name' => 'Delete Departments', 'description' => 'Can delete departments'],

            // Job Levels
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

        // -------------------------
        // Roles
        // -------------------------
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Full access to system'],
            ['name' => 'manager', 'display_name' => 'Manager', 'description' => 'Can manage employees and training'],
            ['name' => 'employee', 'display_name' => 'Employee', 'description' => 'Regular employee'],
            ['name' => 'hr', 'display_name' => 'HR Staff', 'description' => 'Human Resources Staff'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------
        // Role-Permission Pivot
        // -------------------------
        $allRoles = DB::table('roles')->get();
        $allPermissions = DB::table('permissions')->get();

        foreach ($allRoles as $role) {
            if ($role->name === 'admin') {
                foreach ($allPermissions as $permission) {
                    DB::table('permission_role')->updateOrInsert([
                        'role_id' => $role->id,
                        'permission_id' => $permission->id
                    ]);
                }
            }

            if ($role->name === 'hr') {
                $hrPermissions = $allPermissions->whereIn('name', ['view-users', 'create-users', 'edit-users']);
                foreach ($hrPermissions as $permission) {
                    DB::table('permission_role')->updateOrInsert([
                        'role_id' => $role->id,
                        'permission_id' => $permission->id
                    ]);
                }
            }
        }

        // -------------------------
        // Job Levels
        // -------------------------
        $jobLevels = [
            ['name' => 'Director', 'code' => 'DIR', 'level_order' => 1, 'description' => 'Director Level'],
            ['name' => 'Manager', 'code' => 'MGR', 'level_order' => 2, 'description' => 'Manager Level'],
            ['name' => 'Supervisor', 'code' => 'SUP', 'level_order' => 3, 'description' => 'Supervisor Level'],
            ['name' => 'Staff', 'code' => 'STF', 'level_order' => 4, 'description' => 'Staff Level'],
        ];

        foreach ($jobLevels as $level) {
            DB::table('job_levels')->updateOrInsert(
                ['code' => $level['code']],
                [
                    'name' => $level['name'],
                    'level_order' => $level['level_order'],
                    'description' => $level['description'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------
        // Departments
        // -------------------------
        $departments = [
            ['name' => 'IT Department', 'code' => 'IT', 'description' => 'Information Technology'],
            ['name' => 'HR Department', 'code' => 'HR', 'description' => 'Human Resources'],
            ['name' => 'Finance Department', 'code' => 'FIN', 'description' => 'Finance and Accounting'],
            ['name' => 'Operations Department', 'code' => 'OPS', 'description' => 'Operations'],
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->updateOrInsert(
                ['code' => $dept['code']],
                [
                    'name' => $dept['name'],
                    'description' => $dept['description'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // -------------------------
        // Employees
        // -------------------------
        $itDept = DB::table('departments')->where('code', 'IT')->first();
        $hrDept = DB::table('departments')->where('code', 'HR')->first();
        $directorLevel = DB::table('job_levels')->where('code', 'DIR')->first();
        $staffLevel = DB::table('job_levels')->where('code', 'STF')->first();

        $employees = [
            ['employee_id' => 'EMP001', 'name' => 'Admin User', 'email' => 'admin@example.com', 'department_id' => $itDept->id, 'job_level_id' => $directorLevel->id],
            ['employee_id' => 'EMP002', 'name' => 'HR Manager', 'email' => 'hr@example.com', 'department_id' => $hrDept->id, 'job_level_id' => $staffLevel->id],
            ['employee_id' => 'EMP003', 'name' => 'Test User', 'email' => 'test@example.com', 'department_id' => $itDept->id, 'job_level_id' => $staffLevel->id],
        ];

        foreach ($employees as $emp) {
            Employee::updateOrCreate(
                ['employee_id' => $emp['employee_id']],
                [
                    'name' => $emp['name'],
                    'email' => $emp['email'],
                    'department_id' => $emp['department_id'],
                    'job_level_id' => $emp['job_level_id'],
                    'is_active' => true,
                ]
            );
        }

        // -------------------------
        // Users
        // -------------------------
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        $hrRole = DB::table('roles')->where('name', 'hr')->first();

        $users = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'username' => 'admin', 'employee_id' => 'EMP001', 'role_id' => $adminRole->id, 'department_id' => $itDept->id, 'job_level_id' => $directorLevel->id],
            ['name' => 'HR Manager', 'email' => 'hr@example.com', 'username' => 'hrmanager', 'employee_id' => 'EMP002', 'role_id' => $hrRole->id, 'department_id' => $hrDept->id, 'job_level_id' => $staffLevel->id],
            ['name' => 'Test User', 'email' => 'test@example.com', 'username' => 'testuser', 'employee_id' => 'EMP003', 'role_id' => $hrRole->id, 'department_id' => $itDept->id, 'job_level_id' => $staffLevel->id],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'employee_id' => $user['employee_id'],
                    'role_id' => $user['role_id'],
                    'department_id' => $user['department_id'],
                    'job_level_id' => $user['job_level_id'],
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
