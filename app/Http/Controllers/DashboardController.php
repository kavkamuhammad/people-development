<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Role;
use App\Models\Training;
use App\Models\TrainingPeserta;
use App\Models\EvaluasiTrainer;
use App\Models\EvaluasiAtasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Existing stats
        $totalUsers = User::count();
        $totalEmployees = Employee::where('is_active', true)->count();
        $totalDepartments = Department::where('is_active', true)->count();
        $totalRoles = Role::count();
        $recentUsers = User::with('role')->latest()->take(5)->get();

        // Training Statistics
        $totalTrainings = Training::count();
        $totalPeserta = TrainingPeserta::count();
        
        $upcomingTrainings = Training::where('tanggal_training', '>=', now())->count();
        $completedTrainings = Training::where('tanggal_training', '<', now())->count();

        // Average Scores
        $averagePretest = TrainingPeserta::avg('skor_pretest') ?? 0;
        $averagePosttest = TrainingPeserta::avg('skor_posttest') ?? 0;
        $averageNGain = TrainingPeserta::avg('n_gain') ?? 0;

        // Kategori Distribution
        $kategoriDistribution = TrainingPeserta::select('kategori_n_gain', DB::raw('count(*) as total'))
            ->whereNotNull('kategori_n_gain')
            ->groupBy('kategori_n_gain')
            ->get()
            ->pluck('total', 'kategori_n_gain')
            ->toArray();

        // Evaluasi Trainer Statistics
        $totalEvaluasiTrainer = EvaluasiTrainer::count();

        // Get evaluation chart data for recent trainings (last 5)
        $evaluasiChartData = [];
        $recentTrainingsWithEval = Training::whereHas('evaluasiTrainer')
            ->with(['trainer', 'materiTraining'])
            ->latest('tanggal_training')
            ->take(5)
            ->get();

        foreach ($recentTrainingsWithEval as $training) {
            $stats = EvaluasiTrainer::getStatistikByTraining($training->id);
            
            $evaluasiChartData[] = [
                'training' => $training->materiTraining->nama_materi ?? 'N/A',
                'tanggal' => $training->tanggal_training->format('d/m/Y'),
                'trainer' => $training->trainer->nama_trainer ?? '-',
                'statistics' => $stats
            ];
        }

        // Trainer Performance Summary
        $trainerPerformance = DB::table('evaluasi_trainer')
            ->join('trainers', 'evaluasi_trainer.trainer_id', '=', 'trainers.id')
            ->select('trainers.nama_trainer as nama', 'trainers.id')
            ->selectRaw('COUNT(*) as total_evaluasi')
            ->groupBy('trainers.id', 'trainers.nama_trainer')
            ->get()
            ->map(function ($trainer) {
                $trainer->avg_rating = EvaluasiTrainer::getAverageRatingByTrainer($trainer->id);
                return $trainer;
            })
            ->sortByDesc('avg_rating')
            ->take(10);

        // Evaluasi Atasan Statistics
        $totalEvaluasiAtasan = EvaluasiAtasan::count();
        
        $evaluasiAtasanKategori = EvaluasiAtasan::select('kategori', DB::raw('count(*) as jumlah'))
            ->whereNotNull('kategori')
            ->groupBy('kategori')
            ->get()
            ->pluck('jumlah', 'kategori')
            ->toArray();

        // Average by Department
        $departmentPerformance = DB::table('evaluasi_atasan')
            ->select('department')
            ->selectRaw('AVG(total_skor) as avg_skor')
            ->selectRaw('COUNT(*) as jumlah')
            ->groupBy('department')
            ->orderByDesc('avg_skor')
            ->get();

        // Training by Month (last 6 months)
        $trainingByMonth = Training::select(
                DB::raw('DATE_FORMAT(tanggal_training, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->where('tanggal_training', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent Trainings
        $recentTrainings = Training::with(['trainer', 'materiTraining'])
            ->latest('tanggal_training')
            ->take(5)
            ->get();

        // Top Performers
        $topPerformers = DB::table('training_peserta')
            ->join('employees', 'training_peserta.employee_id', '=', 'employees.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->select('employees.name as nama', 'departments.name as department')
            ->selectRaw('AVG(training_peserta.skor_posttest) as avg_score')
            ->selectRaw('COUNT(DISTINCT training_peserta.training_id) as total_training')
            ->where('employees.is_active', true)
            ->groupBy('employees.id', 'employees.name', 'departments.name')
            ->having('total_training', '>=', 2)
            ->orderByDesc('avg_score')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        |  Tambahan: Cek Training yang Belum Dievaluasi Peserta
        |--------------------------------------------------------------------------
        */
        $trainingPerluEvaluasi = Training::with(['peserta', 'evaluasiTrainer'])
            ->where('tanggal_training', '<=', now())
            ->whereHas('peserta', function($query) {
                $query->whereDoesntHave('evaluasiTrainer');
            })
            ->count();

        return view('dashboard', compact(
            // Original stats
            'totalUsers',
            'totalEmployees',
            'totalDepartments',
            'totalRoles',
            'recentUsers',
            // Training stats
            'totalTrainings',
            'totalPeserta',
            'upcomingTrainings',
            'completedTrainings',
            'averagePretest',
            'averagePosttest',
            'averageNGain',
            'kategoriDistribution',
            // Evaluasi Trainer
            'totalEvaluasiTrainer',
            'evaluasiChartData',
            'trainerPerformance',
            // Evaluasi Atasan
            'totalEvaluasiAtasan',
            'evaluasiAtasanKategori',
            'departmentPerformance',
            // Additional
            'trainingByMonth',
            'recentTrainings',
            'topPerformers',
            // Training needing evaluation
            'trainingPerluEvaluasi'
        ));
    }
}
