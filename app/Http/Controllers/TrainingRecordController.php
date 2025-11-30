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
        // Get all trainings with status selesai for selection
        $trainings = \App\Models\Training::where('status', 'selesai')
            ->with(['materiTraining', 'trainer'])
            ->orderBy('tanggal_training', 'desc')
            ->get();

        $selectedTraining = null;
        $pesertaData = collect();

        if ($request->filled('training_id')) {
            $selectedTraining = \App\Models\Training::with(['materiTraining', 'trainer'])
                ->findOrFail($request->training_id);

            // Get peserta with their scores and evaluations
            $query = \App\Models\TrainingPeserta::where('training_id', $request->training_id)
                ->with(['employee.department', 'evaluasiAtasan']);

            // Filter by name
            if ($request->filled('search')) {
                $query->whereHas('employee', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                // We'll filter after calculation
            }

            $pesertaData = $query->get()
                ->map(function($peserta) {
                    $nilaiPosttest = $peserta->skor_posttest ?? 0;
                    $evaluasiAtasan = $peserta->evaluasiAtasan;
                    $skorEvaluasi = $evaluasiAtasan ? $evaluasiAtasan->total_skor : 0;

                    // Hitung total point dengan rumus
                    if ($skorEvaluasi < 13) {
                        $totalPoint = $nilaiPosttest - $skorEvaluasi;
                    } else {
                        $totalPoint = $nilaiPosttest + $skorEvaluasi;
                    }

                    // Tentukan status
                    $status = $totalPoint >= 90 ? 'Lulus' : 'Tidak Lulus';

                    // Tentukan catatan
                    $catatan = $status === 'Lulus' 
                        ? 'Kemampuan & pemahaman materi hampir sempurna'
                        : 'Perlu peningkatan kemampuan & pemahaman materi';

                    return [
                        'id' => $peserta->id,
                        'nama_karyawan' => $peserta->employee->name,
                        'department' => $peserta->employee->department->name ?? '-',
                        'materi_training' => $peserta->training->materiTraining->nama_materi ?? '-',
                        'trainer' => $peserta->training->trainer->nama_trainer ?? '-',
                        'tanggal_training' => $peserta->training->tanggal_training->format('d M Y'),
                        'pemahaman_peserta' => $nilaiPosttest,
                        'skor_evaluasi' => $skorEvaluasi,
                        'total_point' => $totalPoint,
                        'status' => $status,
                        'catatan' => $catatan,
                        'has_evaluasi' => $evaluasiAtasan ? true : false,
                    ];
                });

            // Filter by status if provided
            if ($request->filled('status')) {
                $pesertaData = $pesertaData->filter(function($peserta) use ($request) {
                    return $peserta['status'] === $request->status;
                });
            }
        }

        return view('training-record.index', compact('trainings', 'selectedTraining', 'pesertaData'));
    }

    /**
     * Export training record to Excel
     */
    public function exportExcel(Request $request)
    {
        if (!$request->filled('training_id')) {
            return redirect()->route('training-record.index')
                ->with('error', 'Pilih training terlebih dahulu');
        }

        $training = \App\Models\Training::with(['materiTraining', 'trainer'])
            ->findOrFail($request->training_id);

        $pesertaData = \App\Models\TrainingPeserta::where('training_id', $request->training_id)
            ->with(['employee.department', 'evaluasiAtasan'])
            ->get()
            ->map(function($peserta) {
                $nilaiPosttest = $peserta->skor_posttest ?? 0;
                $evaluasiAtasan = $peserta->evaluasiAtasan;
                $skorEvaluasi = $evaluasiAtasan ? $evaluasiAtasan->total_skor : 0;

                if ($skorEvaluasi < 13) {
                    $totalPoint = $nilaiPosttest - $skorEvaluasi;
                } else {
                    $totalPoint = $nilaiPosttest + $skorEvaluasi;
                }

                $status = $totalPoint >= 90 ? 'Lulus' : 'Tidak Lulus';
                $catatan = $status === 'Lulus' 
                    ? 'Kemampuan & pemahaman materi hampir sempurna'
                    : 'Perlu peningkatan kemampuan & pemahaman materi';

                return [
                    'nama_karyawan' => $peserta->employee->name,
                    'department' => $peserta->employee->department->name ?? '-',
                    'pemahaman_peserta' => $nilaiPosttest,
                    'skor_evaluasi' => $skorEvaluasi,
                    'total_point' => $totalPoint,
                    'status' => $status,
                    'catatan' => $catatan,
                ];
            });

        return view('training-record.export-excel', compact('training', 'pesertaData'));
    }

    /**
     * Display the specified employee's training record
     */
    public function show(Employee $employee)
    {
        $trainingRecords = TrainingPeserta::where('employee_id', $employee->id)
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
        $trainingRecords = TrainingPeserta::where('employee_id', $employee->id)
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
        $department_id = $request->input('department_id');

        $data = DB::table('training_peserta')
            ->join('employees', 'training_peserta.employee_id', '=', 'employees.id')
            ->join('trainings', 'training_peserta.training_id', '=', 'trainings.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->select(
                'departments.name as department_name',
                DB::raw('COUNT(*) as total_training'),
                DB::raw('AVG(training_peserta.skor_pretest) as avg_pretest'),
                DB::raw('AVG(training_peserta.skor_posttest) as avg_posttest'),
                DB::raw('AVG(training_peserta.n_gain) as avg_n_gain'),
                DB::raw('SUM(CASE WHEN training_peserta.kategori = "Tinggi" THEN 1 ELSE 0 END) as tinggi'),
                DB::raw('SUM(CASE WHEN training_peserta.kategori = "Sedang" THEN 1 ELSE 0 END) as sedang'),
                DB::raw('SUM(CASE WHEN training_peserta.kategori = "Rendah" THEN 1 ELSE 0 END) as rendah')
            )
            ->where('employees.is_active', true)
            ->when($department_id, function ($query) use ($department_id) {
                return $query->where('employees.department_id', $department_id);
            })
            ->groupBy('departments.id', 'departments.name')
            ->get();

        $departments = Employee::where('is_active', true)
            ->distinct()
            ->pluck('departement')
            ->sort();

        return view('training-record.report-department', compact('data', 'departments', 'departement'));
    }
}