<?php

namespace App\Http\Controllers;

use App\Models\JobLevel;
use Illuminate\Http\Request;

class JobLevelController extends Controller
{
    
    public function index()
    {
        $jobLevels = JobLevel::with('employees')->orderBy('level_order')->get();
        return view('job-levels.index', compact('jobLevels'));
    }

    public function create()
    {
        return view('job-levels.create');
    }

    public function edit(JobLevel $jobLevel)
    {
        return view('job-levels.edit', compact('jobLevel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:job_levels,code',
            'level_order' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $data = $request->all();
        // Handle checkbox - jika tidak dicentang, set ke false (0)
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        JobLevel::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Level Jabatan berhasil ditambahkan!'
        ]);
    }

    public function update(Request $request, JobLevel $jobLevel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:job_levels,code,' . $jobLevel->id,
            'level_order' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $data = $request->all();
        // Handle checkbox - jika tidak dicentang, set ke false (0)
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $jobLevel->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data Level Jabatan berhasil diupdate!'
        ]);
    }

    public function destroy(JobLevel $jobLevel)
    {
        try {
            // Cek apakah job level masih digunakan oleh karyawan
            $employeeCount = $jobLevel->employees()->count();
            if ($employeeCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus level jabatan karena masih memiliki karyawan!'
                ], 400);
            }

            $jobLevel->delete();
            return response()->json([
                'success' => true,
                'message' => 'Level Jabatan berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus level jabatan karena masih memiliki data terkait!'
            ], 400);
        }
    }
}