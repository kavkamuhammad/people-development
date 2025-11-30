<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'view-users', 'display_name' => 'View Users', 'description' => 'Can view user list'],
            ['name' => 'create-users', 'display_name' => 'Create Users', 'description' => 'Can create new users'],
            ['name' => 'edit-users', 'display_name' => 'Edit Users', 'description' => 'Can edit existing users'],
            ['name' => 'delete-users', 'display_name' => 'Delete Users', 'description' => 'Can delete users'],

            // Role Management
            ['name' => 'view-roles', 'display_name' => 'View Roles', 'description' => 'Can view roles'],
            ['name' => 'create-roles', 'display_name' => 'Create Roles', 'description' => 'Can create roles'],
            ['name' => 'edit-roles', 'display_name' => 'Edit Roles', 'description' => 'Can edit roles'],
            ['name' => 'delete-roles', 'display_name' => 'Delete Roles', 'description' => 'Can delete roles'],

            // Permission Management
            ['name' => 'view-permissions', 'display_name' => 'View Permissions', 'description' => 'Can view permissions'],
            ['name' => 'create-permissions', 'display_name' => 'Create Permissions', 'description' => 'Can create permissions'],
            ['name' => 'edit-permissions', 'display_name' => 'Edit Permissions', 'description' => 'Can edit permissions'],
            ['name' => 'delete-permissions', 'display_name' => 'Delete Permissions', 'description' => 'Can delete permissions'],

            // Employee Management
            ['name' => 'view-employees', 'display_name' => 'View Employees', 'description' => 'Can view employee list'],
            ['name' => 'create-employees', 'display_name' => 'Create Employees', 'description' => 'Can create new employees'],
            ['name' => 'edit-employees', 'display_name' => 'Edit Employees', 'description' => 'Can edit existing employees'],
            ['name' => 'delete-employees', 'display_name' => 'Delete Employees', 'description' => 'Can delete employees'],

            // Department Management
            ['name' => 'view-departments', 'display_name' => 'View Departments', 'description' => 'Can view departments'],
            ['name' => 'create-departments', 'display_name' => 'Create Departments', 'description' => 'Can create departments'],
            ['name' => 'edit-departments', 'display_name' => 'Edit Departments', 'description' => 'Can edit departments'],
            ['name' => 'delete-departments', 'display_name' => 'Delete Departments', 'description' => 'Can delete departments'],

            // Job Level Management
            ['name' => 'view-job-levels', 'display_name' => 'View Job Levels', 'description' => 'Can view job levels'],
            ['name' => 'create-job-levels', 'display_name' => 'Create Job Levels', 'description' => 'Can create job levels'],
            ['name' => 'edit-job-levels', 'display_name' => 'Edit Job Levels', 'description' => 'Can edit job levels'],
            ['name' => 'delete-job-levels', 'display_name' => 'Delete Job Levels', 'description' => 'Can delete job levels'],

            // Trainer Management
            ['name' => 'view-trainers', 'display_name' => 'View Trainers', 'description' => 'Can view trainer list'],
            ['name' => 'create-trainers', 'display_name' => 'Create Trainers', 'description' => 'Can create new trainers'],
            ['name' => 'edit-trainers', 'display_name' => 'Edit Trainers', 'description' => 'Can edit existing trainers'],
            ['name' => 'delete-trainers', 'display_name' => 'Delete Trainers', 'description' => 'Can delete trainers'],

            // Materi Training Management
            ['name' => 'view-materi-trainings', 'display_name' => 'View Materi Trainings', 'description' => 'Can view training materials'],
            ['name' => 'create-materi-trainings', 'display_name' => 'Create Materi Trainings', 'description' => 'Can create training materials'],
            ['name' => 'edit-materi-trainings', 'display_name' => 'Edit Materi Trainings', 'description' => 'Can edit training materials'],
            ['name' => 'delete-materi-trainings', 'display_name' => 'Delete Materi Trainings', 'description' => 'Can delete training materials'],

            // Training Management
            ['name' => 'view-trainings', 'display_name' => 'View Trainings', 'description' => 'Can view training list'],
            ['name' => 'create-trainings', 'display_name' => 'Create Trainings', 'description' => 'Can create new trainings'],
            ['name' => 'edit-trainings', 'display_name' => 'Edit Trainings', 'description' => 'Can edit existing trainings'],
            ['name' => 'delete-trainings', 'display_name' => 'Delete Trainings', 'description' => 'Can delete trainings'],
            ['name' => 'manage-training-participants', 'display_name' => 'Manage Training Participants', 'description' => 'Can add/remove training participants'],
            ['name' => 'input-training-scores', 'display_name' => 'Input Training Scores', 'description' => 'Can input pretest & posttest scores'],

            // Evaluasi Trainer
            ['name' => 'view-evaluasi-trainer', 'display_name' => 'View Evaluasi Trainer', 'description' => 'Can view trainer evaluations'],
            ['name' => 'create-evaluasi-trainer', 'display_name' => 'Create Evaluasi Trainer', 'description' => 'Can create trainer evaluations'],
            ['name' => 'edit-evaluasi-trainer', 'display_name' => 'Edit Evaluasi Trainer', 'description' => 'Can edit trainer evaluations'],
            ['name' => 'delete-evaluasi-trainer', 'display_name' => 'Delete Evaluasi Trainer', 'description' => 'Can delete trainer evaluations'],

            // Observasi Training
            ['name' => 'view-observasi-training', 'display_name' => 'View Observasi Training', 'description' => 'Can view training observations'],
            ['name' => 'create-observasi-training', 'display_name' => 'Create Observasi Training', 'description' => 'Can create training observations'],
            ['name' => 'edit-observasi-training', 'display_name' => 'Edit Observasi Training', 'description' => 'Can edit training observations'],
            ['name' => 'delete-observasi-training', 'display_name' => 'Delete Observasi Training', 'description' => 'Can delete training observations'],

            // Evaluasi Atasan
            ['name' => 'view-evaluasi-atasan', 'display_name' => 'View Evaluasi Atasan', 'description' => 'Can view supervisor evaluations'],
            ['name' => 'create-evaluasi-atasan', 'display_name' => 'Create Evaluasi Atasan', 'description' => 'Can create supervisor evaluations'],
            ['name' => 'edit-evaluasi-atasan', 'display_name' => 'Edit Evaluasi Atasan', 'description' => 'Can edit supervisor evaluations'],
            ['name' => 'delete-evaluasi-atasan', 'display_name' => 'Delete Evaluasi Atasan', 'description' => 'Can delete supervisor evaluations'],

            // Training Record
            ['name' => 'view-training-records', 'display_name' => 'View Training Records', 'description' => 'Can view training records'],
            ['name' => 'export-training-records', 'display_name' => 'Export Training Records', 'description' => 'Can export training records to Excel'],

            // Dashboard
            ['name' => 'view-dashboard', 'display_name' => 'View Dashboard', 'description' => 'Can access dashboard'],
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
