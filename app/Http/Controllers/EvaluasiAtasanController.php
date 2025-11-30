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
            ->paginate(10);
        
        // Evaluasi yang sudah dibuat
        $evaluasiSaya = EvaluasiAtasan::where('atasan_id', $user->id)
            ->with(['training.materiTraining', 'peserta.employee'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('evaluasi-atasan.index', compact('karyawanPerluEvaluasi', 'evaluasiSaya'));
    }

    public function create($trainingId)
    {
        $training = \App\Models\Training::with(['materiTraining', 'trainer', 'peserta.employee.department', 'peserta.employee.jobLevel'])
            ->findOrFail($trainingId);
        
        $user = Auth::user();
        
        // Ambil peserta dari department yang sama dengan user yang sudah evaluasi
        $pesertaSudahEvaluasi = $training->peserta()
            ->whereHas('employee', function($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->whereHas('evaluasiAtasan')
            ->with(['employee.department', 'employee.jobLevel'])
            ->get();
        
        // Ambil peserta dari department yang sama dengan user yang belum evaluasi
        $pesertaBelumEvaluasi = $training->peserta()
            ->whereHas('employee', function($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->whereDoesntHave('evaluasiAtasan')
            ->with(['employee.department', 'employee.jobLevel'])
            ->get();
        
        return view('evaluasi-atasan.create', compact('training', 'pesertaSudahEvaluasi', 'pesertaBelumEvaluasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'evaluasi' => 'required|array',
            'evaluasi.*.peningkatan_keterampilan' => 'required|integer|min:1|max:5',
            'evaluasi.*.uraian_keterampilan' => 'nullable|string',
            'evaluasi.*.penerapan_pengetahuan' => 'required|integer|min:1|max:5',
            'evaluasi.*.uraian_penerapan' => 'nullable|string',
            'evaluasi.*.perubahan_perilaku' => 'required|integer|min:1|max:5',
            'evaluasi.*.uraian_perilaku' => 'nullable|string',
            'evaluasi.*.dampak_performa' => 'required|integer|min:1|max:5',
            'evaluasi.*.uraian_performa' => 'nullable|string',
            'evaluasi.*.catatan_atasan' => 'nullable|string',
        ], [
            'evaluasi.required' => 'Pilih minimal 1 peserta untuk dievaluasi',
            'evaluasi.*.peningkatan_keterampilan.required' => 'Skor peningkatan keterampilan wajib diisi',
            'evaluasi.*.penerapan_pengetahuan.required' => 'Skor penerapan pengetahuan wajib diisi',
            'evaluasi.*.perubahan_perilaku.required' => 'Skor perubahan perilaku wajib diisi',
            'evaluasi.*.dampak_performa.required' => 'Skor dampak performa wajib diisi',
        ]);

        $training = \App\Models\Training::with(['materiTraining'])->findOrFail($request->training_id);
        $created = 0;

        foreach ($request->evaluasi as $pesertaId => $data) {
            $trainingPeserta = TrainingPeserta::with(['employee.department'])
                ->findOrFail($pesertaId);
            
            $totalSkor = $data['peningkatan_keterampilan'] + 
                        $data['penerapan_pengetahuan'] + 
                        $data['perubahan_perilaku'] + 
                        $data['dampak_performa'];
            
            // Tentukan kategori
            if ($totalSkor >= 17) {
                $kategori = 'Sangat Baik';
            } elseif ($totalSkor >= 13) {
                $kategori = 'Baik';
            } elseif ($totalSkor >= 9) {
                $kategori = 'Cukup';
            } else {
                $kategori = 'Perlu Perbaikan';
            }

            EvaluasiAtasan::create([
                'training_id' => $training->id,
                'peserta_id' => $pesertaId,
                'atasan_id' => Auth::id(),
                'nama_karyawan' => $trainingPeserta->employee->name,
                'materi_training' => $training->materiTraining->nama_materi,
                'department' => $trainingPeserta->employee->department->name,
                'tanggal_training' => $training->tanggal_training,
                'peningkatan_keterampilan' => $data['peningkatan_keterampilan'],
                'uraian_peningkatan_keterampilan' => $data['uraian_keterampilan'] ?? null,
                'penerapan_ilmu' => $data['penerapan_pengetahuan'],
                'uraian_penerapan_ilmu' => $data['uraian_penerapan'] ?? null,
                'perubahan_perilaku' => $data['perubahan_perilaku'],
                'uraian_perubahan_perilaku' => $data['uraian_perilaku'] ?? null,
                'dampak_performa' => $data['dampak_performa'],
                'uraian_dampak_performa' => $data['uraian_performa'] ?? null,
                'total_skor' => $totalSkor,
                'kategori' => $kategori,
                'catatan_atasan' => $data['catatan_atasan'] ?? null,
            ]);

            $created++;
        }

        return redirect()->route('evaluasi-atasan.index')
            ->with('success', "Berhasil menyimpan evaluasi untuk {$created} peserta");
    }

    public function show(EvaluasiAtasan $evaluasiAtasan)
    {
        $evaluasiAtasan->load(['training.materiTraining', 'peserta.employee.department', 'atasan']);
        
        return view('evaluasi-atasan.show', compact('evaluasiAtasan'));
    }

    public function print(EvaluasiAtasan $evaluasiAtasan)
    {
        $evaluasiAtasan->load(['training.materiTraining', 'training.trainer', 'peserta.employee.department', 'peserta.employee.jobLevel', 'atasan.department']);
        
        return view('evaluasi-atasan.print', compact('evaluasiAtasan'));
    }

    public function edit(EvaluasiAtasan $evaluasiAtasan)
    {
        // Cek apakah user adalah evaluator
        if ($evaluasiAtasan->atasan_id != Auth::id()) {
            return redirect()->route('evaluasi-atasan.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit evaluasi ini');
        }
        
        $evaluasiAtasan->load(['training.materiTraining', 'peserta.employee']);
        
        return view('evaluasi-atasan.edit', compact('evaluasiAtasan'));
    }

    public function update(Request $request, EvaluasiAtasan $evaluasiAtasan)
    {
        // Cek apakah user adalah evaluator
        if ($evaluasiAtasan->atasan_id != Auth::id()) {
            return redirect()->route('evaluasi-atasan.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit evaluasi ini');
        }

        $request->validate([
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
            'peningkatan_keterampilan' => $request->peningkatan_keterampilan,
            'uraian_peningkatan_keterampilan' => $request->uraian_keterampilan,
            'penerapan_ilmu' => $request->penerapan_pengetahuan,
            'uraian_penerapan_ilmu' => $request->uraian_penerapan,
            'perubahan_perilaku' => $request->perubahan_perilaku,
            'uraian_perubahan_perilaku' => $request->uraian_perilaku,
            'dampak_performa' => $request->dampak_performa,
            'uraian_dampak_performa' => $request->uraian_performa,
            'catatan_atasan' => $request->catatan_atasan,
        ]);

        return redirect()->route('evaluasi-atasan.show', $evaluasiAtasan)
            ->with('success', 'Evaluasi atasan berhasil diupdate');
    }
}