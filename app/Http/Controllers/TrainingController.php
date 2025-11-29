<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingPeserta;
use App\Models\Trainer;
use App\Models\MateriTraining;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with(['trainer', 'materiTraining', 'peserta'])
            ->orderBy('tanggal_training', 'desc')
            ->paginate(15);
            
        return view('training.index', compact('trainings'));
    }

    public function create()
    {
        $trainers = Trainer::where('status', 1)->get();
        $materiTrainings = MateriTraining::all();
        $employees = Employee::where('is_active', true)
            ->with(['department', 'jobLevel'])
            ->get();
            
        return view('training.create', compact('trainers', 'materiTrainings', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'materi_training_id' => 'required|exists:materi_trainings,id',
            'tanggal_training' => 'required|date',
            'jumlah_soal' => 'required|integer|min:1',
            'peserta' => 'required|array|min:1',
            'peserta.*.employee_id' => 'required|exists:employees,id',
            'peserta.*.pretest_benar' => 'required|integer|min:0',
            'peserta.*.posttest_benar' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            // Create Training
            $training = Training::create([
                'trainer_id' => $request->trainer_id,
                'materi_training_id' => $request->materi_training_id,
                'tanggal_training' => $request->tanggal_training,
                'jumlah_soal' => $request->jumlah_soal,
                'jenis_training' => $request->jenis_training,
                'keterangan' => $request->keterangan,
                'status' => 'selesai', // Set default selesai atau sesuaikan
            ]);

            // Create Training Peserta
            foreach ($request->peserta as $peserta) {
                TrainingPeserta::create([
                    'training_id' => $training->id,
                    'employee_id' => $peserta['employee_id'],
                    'pretest_benar' => $peserta['pretest_benar'],
                    'posttest_benar' => $peserta['posttest_benar'],
                    'status_peserta' => $peserta['status_peserta'] ?? 'Lulus',
                    'catatan' => $peserta['catatan'] ?? null,
                ]);
            }
        });

        return redirect()->route('training.index')
            ->with('success', 'Data training berhasil ditambahkan');
    }

    public function show(Training $training)
    {
        $training->load(['trainer', 'materiTraining', 'peserta.employee.department', 'peserta.employee.jobLevel']);
        
        return view('training.show', compact('training'));
    }

    public function edit(Training $training)
    {
        $trainers = Trainer::where('status', 1)->get();
        $materiTrainings = MateriTraining::all();
        $employees = Employee::where('is_active', true)
            ->with(['department', 'jobLevel'])
            ->get();
        
        $training->load(['peserta.employee']);
        
        return view('training.edit', compact('training', 'trainers', 'materiTrainings', 'employees'));
    }

    public function update(Request $request, Training $training)
    {
        $request->validate([
            'trainer_id' => 'required|exists:trainers,id',
            'materi_training_id' => 'required|exists:materi_trainings,id',
            'tanggal_training' => 'required|date',
            'jumlah_soal' => 'required|integer|min:1',
            'peserta' => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request, $training) {
            $training->update([
                'trainer_id' => $request->trainer_id,
                'materi_training_id' => $request->materi_training_id,
                'tanggal_training' => $request->tanggal_training,
                'jumlah_soal' => $request->jumlah_soal,
                'jenis_training' => $request->jenis_training,
                'keterangan' => $request->keterangan,
            ]);

            // Delete old peserta
            $training->peserta()->delete();

            // Create new peserta
            foreach ($request->peserta as $peserta) {
                TrainingPeserta::create([
                    'training_id' => $training->id,
                    'employee_id' => $peserta['employee_id'],
                    'pretest_benar' => $peserta['pretest_benar'],
                    'posttest_benar' => $peserta['posttest_benar'],
                    'status_peserta' => $peserta['status_peserta'] ?? 'Lulus',
                    'catatan' => $peserta['catatan'] ?? null,
                ]);
            }
        });

        return redirect()->route('training.index')
            ->with('success', 'Data training berhasil diupdate');
    }

    public function destroy(Training $training)
    {
        DB::transaction(function () use ($training) {
            $training->peserta()->delete();
            $training->delete();
        });
        
        return redirect()->route('training.index')
            ->with('success', 'Data training berhasil dihapus');
    }
}