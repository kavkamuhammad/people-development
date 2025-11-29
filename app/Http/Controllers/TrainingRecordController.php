<?php

namespace App\Http\Controllers;

use App\Models\TrainingPeserta;
use App\Models\Employee; // Sesuaikan dengan model Karyawan Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingRecordController extends Controller
{
    /**
     * Display a listing of employees with training records
     */
    public function index(Request $request)
    {
        $query = Employee::with(['trainingRecords.training.trainer']);

        // Filter by department
        if ($request->filled('departement')) {
            $query->where('departement', $request->departement);
        }

        // Filter by name
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $employees = $query->where('is_active', true)
            ->orderBy('nama')
            ->paginate(20);

        // Get departments for filter
        $departments = Employee::where('is_active', true)
            ->distinct()
            ->pluck('departement')
            ->sort();

        return view('training-record.index', compact('employees', 'departments'));
    }

    /**
     * Display the specified employee's training record
     */
    public function show(Employee $employee)
    {
        $trainingRecords = TrainingPeserta::where('karyawan_id', $employee->id)
            ->with(['training.trainer', 'evaluasiAtasan'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalTraining = $trainingRecords->count();
        $averagePretest = $trainingRecords->avg('skor_pretest') ?? 0;
        $averagePosttest = $trainingRecords->avg('skor_posttest') ?? 0;
        $averageNGain = $trainingRecords->avg('n_gain') ?? 0;

        // Count by category
        $kategoriCount = [
            'Tinggi' => $trainingRecords->where('kategori', 'Tinggi')->count(),
            'Sedang' => $trainingRecords->where('kategori', 'Sedang')->count(),
            'Rendah' => $trainingRecords->where('kategori', 'Rendah')->count(),
        ];

        // Training by year
        $trainingByYear = $trainingRecords->groupBy(function ($record) {
            return $record->training->tanggal->format('Y');
        })->map(function ($group) {
            return $group->count();
        })->sortKeys();

        return view('training-record.show', compact(
            'employee',
            'trainingRecords',
            'totalTraining',
            'averagePretest',
            'averagePosttest',
            'averageNGain',
            'kategoriCount',
            'trainingByYear'
        ));
    }

    /**
     * Export employee's training record
     */
    public function export(Employee $employee)
    {
        $trainingRecords = TrainingPeserta::where('karyawan_id', $employee->id)
            ->with(['training.trainer', 'evaluasiAtasan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('training-record.export', compact('employee', 'trainingRecords'));
    }

    /**
     * Compare multiple employees
     */
    public function compare(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array|min:2|max:5',
            'employee_ids.*' => 'exists:employees,id'
        ]);

        $employees = Employee::whereIn('id', $request->employee_ids)
            ->with(['trainingRecords.training'])
            ->get();

        $comparison = [];
        foreach ($employees as $employee) {
            $records = $employee->trainingRecords;
            
            $comparison[] = [
                'employee' => $employee,
                'total_training' => $records->count(),
                'avg_pretest' => round($records->avg('skor_pretest') ?? 0, 2),
                'avg_posttest' => round($records->avg('skor_posttest') ?? 0, 2),
                'avg_n_gain' => round($records->avg('n_gain') ?? 0, 2),
                'kategori_tinggi' => $records->where('kategori', 'Tinggi')->count(),
                'kategori_sedang' => $records->where('kategori', 'Sedang')->count(),
                'kategori_rendah' => $records->where('kategori', 'Rendah')->count(),
            ];
        }

        return view('training-record.compare', compact('comparison'));
    }

    /**
     * Generate report by department
     */
    public function reportByDepartment(Request $request)
    {
        $departement = $request->input('departement');

        $data = DB::table('training_peserta')
            ->join('employees', 'training_peserta.karyawan_id', '=', 'employees.id')
            ->join('trainings', 'training_peserta.training_id', '=', 'trainings.id')
            ->select(
                'employees.departement',
                DB::raw('COUNT(*) as total_training'),
                DB::raw('AVG(training_peserta.skor_pretest) as avg_pretest'),
                DB::raw('AVG(training_peserta.skor_posttest) as avg_posttest'),
                DB::raw('AVG(training_peserta.n_gain) as avg_n_gain'),
                DB::raw('SUM(CASE WHEN training_peserta.kategori = "Tinggi" THEN 1 ELSE 0 END) as tinggi'),
                DB::raw('SUM(CASE WHEN training_peserta.kategori = "Sedang" THEN 1 ELSE 0 END) as sedang'),
                DB::raw('SUM(CASE WHEN training_peserta.kategori = "Rendah" THEN 1 ELSE 0 END) as rendah')
            )
            ->where('employees.is_active', true)
            ->when($departement, function ($query) use ($departement) {
                return $query->where('employees.departement', $departement);
            })
            ->groupBy('employees.departement')
            ->get();

        $departments = Employee::where('is_active', true)
            ->distinct()
            ->pluck('departement')
            ->sort();

        return view('training-record.report-department', compact('data', 'departments', 'departement'));
    }
}