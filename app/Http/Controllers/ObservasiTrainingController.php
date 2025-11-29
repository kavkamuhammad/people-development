<?php

namespace App\Http\Controllers;

use App\Models\ObservasiTraining;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ObservasiTrainingController extends Controller
{
    /**
     * Display a listing of trainings that are completed and ready for observation
     */
    public function index()
    {
        $trainings = Training::with(['trainer', 'materiTraining'])
            ->where('status', 'selesai')
            ->withExists('observasiTraining')
            ->latest('tanggal_training')
            ->paginate(10);

        return view('observasi-training.index', compact('trainings'));
    }

    /**
     * Show the form for creating observation for a training
     */
    public function create(Training $training)
    {
        // Check if training is completed
        if ($training->status !== 'selesai') {
            return redirect()->route('observasi-training.index')
                ->with('error', 'Training ini belum selesai.');
        }

        // Check if already has observation
        if (ObservasiTraining::hasObservation($training->id)) {
            return redirect()->route('observasi-training.show', $training->id)
                ->with('info', 'Training ini sudah diobservasi. Anda bisa edit observasi yang ada.');
        }

        $training->load(['trainer', 'materiTraining']);
        $aspekPenilaian = ObservasiTraining::getAspekPenilaian();
        $penilaianOptions = ObservasiTraining::getPenilaianOptions();

        return view('observasi-training.create', compact(
            'training',
            'aspekPenilaian',
            'penilaianOptions'
        ));
    }

    /**
     * Store observation for a training
     */
    public function store(Request $request, Training $training)
    {
        // Check if already has observation
        if (ObservasiTraining::hasObservation($training->id)) {
            return redirect()->route('observasi-training.show', $training->id)
                ->with('error', 'Training ini sudah diobservasi sebelumnya.');
        }

        $aspekFields = array_keys(ObservasiTraining::getAspekPenilaian());
        $rules = [];
        
        foreach ($aspekFields as $field) {
            $rules[$field] = 'required|in:Ada,Tidak';
        }
        $rules['catatan'] = 'nullable|string';

        $validated = $request->validate($rules, [
            '*.required' => 'Semua aspek penilaian harus diisi',
            '*.in' => 'Nilai penilaian tidak valid',
        ]);

        $observasi = ObservasiTraining::create([
            'user_id' => Auth::id(),
            'training_id' => $training->id,
            'trainer_id' => $training->trainer_id,
            'materi_training' => $training->materiTraining->nama_materi ?? '-',
            'tanggal_training' => $training->tanggal_training,
            'apersepsi' => $validated['apersepsi'],
            'menyampaikan_tujuan_pembelajaran' => $validated['menyampaikan_tujuan_pembelajaran'],
            'learning_brainstorming' => $validated['learning_brainstorming'],
            'modelling' => $validated['modelling'],
            'inquiry_learning_community' => $validated['inquiry_learning_community'],
            'inquiry' => $validated['inquiry'],
            'learning_community' => $validated['learning_community'],
            'authentic_assesment' => $validated['authentic_assesment'],
            'presentasi_hasil_diskusi' => $validated['presentasi_hasil_diskusi'],
            'kesempatan_menanggapi_presentasi' => $validated['kesempatan_menanggapi_presentasi'],
            'klarifikasi_hasil_presentasi' => $validated['klarifikasi_hasil_presentasi'],
            'konstruktivis_reflection' => $validated['konstruktivis_reflection'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('observasi-training.show', $training->id)
            ->with('success', 'Observasi training berhasil disimpan!');
    }

    /**
     * Display the observation result for a training
     */
    public function show($trainingId)
    {
        $observasi = ObservasiTraining::where('training_id', $trainingId)
            ->with(['training.materiTraining', 'training.trainer', 'user'])
            ->firstOrFail();

        $aspekPenilaian = ObservasiTraining::getAspekPenilaian();

        return view('observasi-training.show', compact('observasi', 'aspekPenilaian'));
    }

    /**
     * Show form to edit observation
     */
    public function edit($trainingId)
    {
        $observasi = ObservasiTraining::where('training_id', $trainingId)
            ->with(['training.materiTraining', 'training.trainer'])
            ->firstOrFail();

        $aspekPenilaian = ObservasiTraining::getAspekPenilaian();
        $penilaianOptions = ObservasiTraining::getPenilaianOptions();

        return view('observasi-training.edit', compact(
            'observasi',
            'aspekPenilaian',
            'penilaianOptions'
        ));
    }

    /**
     * Update observation
     */
    public function update(Request $request, $trainingId)
    {
        $observasi = ObservasiTraining::where('training_id', $trainingId)->firstOrFail();

        $aspekFields = array_keys(ObservasiTraining::getAspekPenilaian());
        $rules = [];
        
        foreach ($aspekFields as $field) {
            $rules[$field] = 'required|in:Ada,Tidak';
        }
        $rules['catatan'] = 'nullable|string';

        $validated = $request->validate($rules);

        $observasi->update($validated);

        return redirect()->route('observasi-training.show', $trainingId)
            ->with('success', 'Observasi berhasil diupdate');
    }

    /**
     * Delete observation
     */
    public function destroy($trainingId)
    {
        $observasi = ObservasiTraining::where('training_id', $trainingId)->firstOrFail();
        $observasi->delete();

        return redirect()->route('observasi-training.index')
            ->with('success', 'Observasi berhasil dihapus');
    }
}
