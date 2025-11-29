<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiAtasan;
use App\Models\TrainingPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiAtasanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil karyawan yang perlu dievaluasi (berdasarkan department yang sama atau bawahan langsung)
        $karyawanPerluEvaluasi = TrainingPeserta::whereHas('employee', function($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->whereDoesntHave('evaluasiAtasan')
            ->with(['training.materiTraining', 'training.trainer', 'employee.department', 'employee.jobLevel'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Evaluasi yang sudah dibuat
        $evaluasiSaya = EvaluasiAtasan::where('evaluator_id', $user->id)
            ->with(['trainingPeserta.training.materiTraining', 'trainingPeserta.employee'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('evaluasi-atasan.index', compact('karyawanPerluEvaluasi', 'evaluasiSaya'));
    }

    public function create($trainingPesertaId)
    {
        $trainingPeserta = TrainingPeserta::with(['training.materiTraining', 'training.trainer', 'employee.department', 'employee.jobLevel'])
            ->findOrFail($trainingPesertaId);
        
        // Cek apakah sudah dievaluasi
        $sudahEvaluasi = EvaluasiAtasan::where('training_peserta_id', $trainingPesertaId)->exists();
        
        if ($sudahEvaluasi) {
            return redirect()->route('evaluasi-atasan.index')
                ->with('error', 'Karyawan ini sudah dievaluasi');
        }
        
        return view('evaluasi-atasan.create', compact('trainingPeserta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'training_peserta_id' => 'required|exists:training_peserta,id',
            'nama_atasan' => 'required|string|max:255',
            'jabatan_atasan' => 'required|string|max:255',
            'tanggal_evaluasi' => 'required|date',
            'peningkatan_keterampilan' => 'required|integer|min:1|max:5',
            'uraian_keterampilan' => 'nullable|string',
            'penerapan_pengetahuan' => 'required|integer|min:1|max:5',
            'uraian_penerapan' => 'nullable|string',
            'perubahan_perilaku' => 'required|integer|min:1|max:5',
            'uraian_perilaku' => 'nullable|string',
            'dampak_performa' => 'required|integer|min:1|max:5',
            'uraian_performa' => 'nullable|string',
        ]);

        EvaluasiAtasan::create([
            'training_peserta_id' => $request->training_peserta_id,
            'evaluator_id' => Auth::id(),
            'nama_atasan' => $request->nama_atasan,
            'jabatan_atasan' => $request->jabatan_atasan,
            'tanggal_evaluasi' => $request->tanggal_evaluasi,
            'peningkatan_keterampilan' => $request->peningkatan_keterampilan,
            'uraian_keterampilan' => $request->uraian_keterampilan,
            'penerapan_pengetahuan' => $request->penerapan_pengetahuan,
            'uraian_penerapan' => $request->uraian_penerapan,
            'perubahan_perilaku' => $request->perubahan_perilaku,
            'uraian_perilaku' => $request->uraian_perilaku,
            'dampak_performa' => $request->dampak_performa,
            'uraian_performa' => $request->uraian_performa,
            'catatan_atasan' => $request->catatan_atasan,
        ]);

        return redirect()->route('evaluasi-atasan.index')
            ->with('success', 'Evaluasi atasan berhasil disimpan');
    }

    public function show(EvaluasiAtasan $evaluasiAtasan)
    {
        $evaluasiAtasan->load(['trainingPeserta.training.materiTraining', 'trainingPeserta.employee.department', 'evaluator']);
        
        return view('evaluasi-atasan.show', compact('evaluasiAtasan'));
    }

    public function edit(EvaluasiAtasan $evaluasiAtasan)
    {
        // Cek apakah user adalah evaluator
        if ($evaluasiAtasan->evaluator_id != Auth::id()) {
            return redirect()->route('evaluasi-atasan.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit evaluasi ini');
        }
        
        $evaluasiAtasan->load(['trainingPeserta.training.materiTraining', 'trainingPeserta.employee']);
        
        return view('evaluasi-atasan.edit', compact('evaluasiAtasan'));
    }

    public function update(Request $request, EvaluasiAtasan $evaluasiAtasan)
    {
        $request->validate([
            'nama_atasan' => 'required|string|max:255',
            'jabatan_atasan' => 'required|string|max:255',
            'tanggal_evaluasi' => 'required|date',
            'peningkatan_keterampilan' => 'required|integer|min:1|max:5',
            'uraian_keterampilan' => 'nullable|string',
            'penerapan_pengetahuan' => 'required|integer|min:1|max:5',
            'uraian_penerapan' => 'nullable|string',
            'perubahan_perilaku' => 'required|integer|min:1|max:5',
            'uraian_perilaku' => 'nullable|string',
            'dampak_performa' => 'required|integer|min:1|max:5',
            'uraian_performa' => 'nullable|string',
        ]);

        $evaluasiAtasan->update([
            'nama_atasan' => $request->nama_atasan,
            'jabatan_atasan' => $request->jabatan_atasan,
            'tanggal_evaluasi' => $request->tanggal_evaluasi,
            'peningkatan_keterampilan' => $request->peningkatan_keterampilan,
            'uraian_keterampilan' => $request->uraian_keterampilan,
            'penerapan_pengetahuan' => $request->penerapan_pengetahuan,
            'uraian_penerapan' => $request->uraian_penerapan,
            'perubahan_perilaku' => $request->perubahan_perilaku,
            'uraian_perilaku' => $request->uraian_perilaku,
            'dampak_performa' => $request->dampak_performa,
            'uraian_performa' => $request->uraian_performa,
            'catatan_atasan' => $request->catatan_atasan,
        ]);

        return redirect()->route('evaluasi-atasan.index')
            ->with('success', 'Evaluasi atasan berhasil diupdate');
    }
}