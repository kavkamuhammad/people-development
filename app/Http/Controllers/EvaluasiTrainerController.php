<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiTrainer;
use App\Models\Training;
use App\Models\TrainingPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluasiTrainerController extends Controller
{
    /**
     * Display a listing of trainings that are completed and ready for evaluation
     */
    public function index()
    {
        // Ambil training yang sudah selesai (status = 'selesai')
        $trainings = Training::with(['trainer', 'materiTraining', 'peserta.employee'])
            ->where('status', 'selesai')
            ->withCount('evaluasiTrainer')
            ->latest('tanggal_training')
            ->paginate(10);

        return view('evaluasi-trainer.index', compact('trainings'));
    }

    /**
     * Show the form for creating evaluations for a training
     */
    public function create(Training $training)
    {
        // Pastikan training sudah selesai
        if ($training->status !== 'selesai') {
            return redirect()->route('evaluasi-trainer.index')
                ->with('error', 'Training ini belum selesai.');
        }

        $training->load(['trainer', 'materiTraining', 'peserta.employee.department', 'peserta.employee.jobLevel']);
        
        // Pisahkan peserta yang sudah dan belum dievaluasi
        $pesertaSudahEvaluasi = $training->peserta->filter(function ($peserta) use ($training) {
            return EvaluasiTrainer::where('training_id', $training->id)
                ->where('peserta_id', $peserta->id)
                ->exists();
        });

        $pesertaBelumEvaluasi = $training->peserta->filter(function ($peserta) use ($training) {
            return !EvaluasiTrainer::where('training_id', $training->id)
                ->where('peserta_id', $peserta->id)
                ->exists();
        });

        $indikatorOptions = EvaluasiTrainer::getIndikatorOptions();

        return view('evaluasi-trainer.create', compact(
            'training',
            'pesertaSudahEvaluasi',
            'pesertaBelumEvaluasi',
            'indikatorOptions'
        ));
    }

    /**
     * Store evaluations for multiple participants
     */
    public function store(Request $request, Training $training)
    {
        $validated = $request->validate([
            'peserta_ids' => 'required|array|min:1',
            'peserta_ids.*' => 'required|exists:training_pesertas,id',
            'relevansi_materi' => 'required|in:SB,B,C,K',
            'pemahaman_materi' => 'required|in:SB,B,C,K',
            'penguasaan_trainer' => 'required|in:SB,B,C,K',
            'penyampaian_trainer' => 'required|in:SB,B,C,K',
            'fasilitas' => 'required|in:SB,B,C,K',
            'manfaat_keseluruhan' => 'required|in:SB,B,C,K',
        ]);

        $created = 0;
        
        DB::transaction(function () use ($validated, $training, &$created) {
            foreach ($validated['peserta_ids'] as $pesertaId) {
                // Verify peserta belongs to this training
                $peserta = TrainingPeserta::where('id', $pesertaId)
                    ->where('training_id', $training->id)
                    ->firstOrFail();

                // Check if already evaluated
                $exists = EvaluasiTrainer::where('training_id', $training->id)
                    ->where('peserta_id', $pesertaId)
                    ->exists();

                if ($exists) {
                    continue; // Skip jika sudah ada
                }

                EvaluasiTrainer::create([
                    'training_id' => $training->id,
                    'peserta_id' => $pesertaId,
                    'trainer_id' => $training->trainer_id,
                    'materi_training' => $training->materiTraining->nama_materi ?? '-',
                    'tanggal_training' => $training->tanggal_training,
                    'relevansi_materi' => $validated['relevansi_materi'],
                    'pemahaman_materi' => $validated['pemahaman_materi'],
                    'penguasaan_trainer' => $validated['penguasaan_trainer'],
                    'penyampaian_trainer' => $validated['penyampaian_trainer'],
                    'fasilitas' => $validated['fasilitas'],
                    'manfaat_keseluruhan' => $validated['manfaat_keseluruhan'],
                ]);
                
                $created++;
            }
        });

        return redirect()->route('evaluasi-trainer.create', $training)
            ->with('success', 'Evaluasi trainer berhasil disimpan untuk ' . $created . ' peserta!');
    }

    /**
     * Display the evaluation results for a training
     */
    public function show(Training $training)
    {
        $training->load(['trainer', 'materiTraining', 'peserta.employee.department']);
        
        $evaluasi = EvaluasiTrainer::where('training_id', $training->id)
            ->with(['peserta.employee'])
            ->get();

        $statistik = EvaluasiTrainer::getStatistikByTraining($training->id);
        $indikatorOptions = EvaluasiTrainer::getIndikatorOptions();
        $aspectLabels = EvaluasiTrainer::getAspectLabels();

        return view('evaluasi-trainer.show', compact(
            'training',
            'evaluasi',
            'statistik',
            'indikatorOptions',
            'aspectLabels'
        ));
    }

    /**
     * Show form to edit single evaluation
     */
    public function edit($evaluasiId)
    {
        $evaluasi = EvaluasiTrainer::with([
            'training.materiTraining',
            'training.trainer',
            'peserta.employee.department',
            'peserta.employee.jobLevel'
        ])->findOrFail($evaluasiId);

        $indikatorOptions = EvaluasiTrainer::getIndikatorOptions();

        return view('evaluasi-trainer.edit', compact('evaluasi', 'indikatorOptions'));
    }

    /**
     * Update single evaluation
     */
    public function update(Request $request, $evaluasiId)
    {
        $evaluasi = EvaluasiTrainer::findOrFail($evaluasiId);

        $validated = $request->validate([
            'relevansi_materi' => 'required|in:SB,B,C,K',
            'pemahaman_materi' => 'required|in:SB,B,C,K',
            'penguasaan_trainer' => 'required|in:SB,B,C,K',
            'penyampaian_trainer' => 'required|in:SB,B,C,K',
            'fasilitas' => 'required|in:SB,B,C,K',
            'manfaat_keseluruhan' => 'required|in:SB,B,C,K',
        ]);

        $evaluasi->update($validated);

        return redirect()->route('evaluasi-trainer.show', $evaluasi->training_id)
            ->with('success', 'Evaluasi berhasil diupdate');
    }

    /**
     * Delete single evaluation
     */
    public function destroy($evaluasiId)
    {
        $evaluasi = EvaluasiTrainer::findOrFail($evaluasiId);
        $trainingId = $evaluasi->training_id;
        
        $evaluasi->delete();

        return redirect()->route('evaluasi-trainer.show', $trainingId)
            ->with('success', 'Evaluasi berhasil dihapus');
    }

    /**
     * Delete all evaluations for a training
     */
    public function destroyAll(Training $training)
    {
        EvaluasiTrainer::where('training_id', $training->id)->delete();

        return redirect()->route('evaluasi-trainer.index')
            ->with('success', 'Semua evaluasi untuk training ini berhasil dihapus');
    }
}