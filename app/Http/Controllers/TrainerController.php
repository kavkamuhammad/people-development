<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use App\Models\EvaluasiTrainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // PERBAIKAN: Menggunakan withAvg() Laravel untuk menghitung rata-rata
        $trainers = Trainer::withCount(['evaluasi', 'trainings'])
            ->get()
            ->map(function($trainer) {
                // Hitung average rating manual untuk setiap trainer
                // Konversi nilai enum ke numeric: SB=4, B=3, C=2, K=1
                $avgRating = DB::table('evaluasi_trainer')
                    ->where('trainer_id', $trainer->id)
                    ->selectRaw('AVG((
                        CASE penguasaan_trainer
                            WHEN "SB" THEN 4
                            WHEN "B" THEN 3
                            WHEN "C" THEN 2
                            WHEN "K" THEN 1
                            ELSE 0
                        END +
                        CASE penyampaian_trainer
                            WHEN "SB" THEN 4
                            WHEN "B" THEN 3
                            WHEN "C" THEN 2
                            WHEN "K" THEN 1
                            ELSE 0
                        END +
                        CASE pemahaman_materi
                            WHEN "SB" THEN 4
                            WHEN "B" THEN 3
                            WHEN "C" THEN 2
                            WHEN "K" THEN 1
                            ELSE 0
                        END +
                        CASE manfaat_keseluruhan
                            WHEN "SB" THEN 4
                            WHEN "B" THEN 3
                            WHEN "C" THEN 2
                            WHEN "K" THEN 1
                            ELSE 0
                        END
                    ) / 4) as avg')
                    ->value('avg');

                $trainer->avg_rating = round($avgRating ?? 0, 2);
                return $trainer;
            });

        return view('trainers.index', compact('trainers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trainers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_trainer' => 'required|string|max:50|unique:trainers,kode_trainer',
            'nama_trainer' => 'required|string|max:255',
            'status' => 'required|in:0,1'
        ]);

        $validated['status'] = (bool) $validated['status'];

        Trainer::create($validated);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Trainer berhasil ditambahkan!'
            ]);
        }

        return redirect()->route('trainers.index')
            ->with('success', 'Trainer berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trainer $trainer)
    {
        $trainer->load(['evaluasi.training', 'trainings']);
        
        $statistics = [
            'total_evaluasi' => $trainer->evaluasi->count(),
            'avg_rating' => $trainer->averageRating(),
            'total_training' => $trainer->trainings->count(),
        ];

        return view('trainers.show', compact('trainer', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trainer $trainer)
    {
        return view('trainers.edit', compact('trainer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trainer $trainer)
    {
        $validated = $request->validate([
            'kode_trainer' => 'required|string|max:50|unique:trainers,kode_trainer,' . $trainer->id,
            'nama_trainer' => 'required|string|max:255',
            'status' => 'required|in:0,1'
        ]);

        $validated['status'] = (bool) $validated['status'];

        $trainer->update($validated);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Trainer berhasil diupdate!'
            ]);
        }

        return redirect()->route('trainers.index')
            ->with('success', 'Trainer berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Trainer $trainer)
    {
        try {
            // Check if trainer has related data
            if ($trainer->evaluasi()->count() > 0 || $trainer->trainings()->count() > 0) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Trainer tidak dapat dihapus karena memiliki data terkait!'
                    ], 400);
                }
                return redirect()->route('trainers.index')
                    ->with('error', 'Trainer tidak dapat dihapus karena memiliki data terkait!');
            }

            $trainer->delete();

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Trainer berhasil dihapus!'
                ]);
            }

            return redirect()->route('trainers.index')
                ->with('success', 'Trainer berhasil dihapus!');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus trainer!'
                ], 500);
            }
            return redirect()->route('trainers.index')
                ->with('error', 'Terjadi kesalahan saat menghapus trainer!');
        }
    }

    /**
     * Get trainer performance statistics
     */
    public function performance(Trainer $trainer)
    {
        // PERBAIKAN: Menggunakan nama kolom yang benar dan konversi enum ke numeric
        $evaluasiStats = DB::table('evaluasi_trainer')
            ->where('trainer_id', $trainer->id)
            ->selectRaw('
                COUNT(*) as total,
                AVG(CASE penguasaan_trainer
                    WHEN "SB" THEN 4
                    WHEN "B" THEN 3
                    WHEN "C" THEN 2
                    WHEN "K" THEN 1
                    ELSE 0
                END) as avg_penguasaan,
                AVG(CASE penyampaian_trainer
                    WHEN "SB" THEN 4
                    WHEN "B" THEN 3
                    WHEN "C" THEN 2
                    WHEN "K" THEN 1
                    ELSE 0
                END) as avg_metode,
                AVG(CASE pemahaman_materi
                    WHEN "SB" THEN 4
                    WHEN "B" THEN 3
                    WHEN "C" THEN 2
                    WHEN "K" THEN 1
                    ELSE 0
                END) as avg_pemahaman,
                AVG(CASE manfaat_keseluruhan
                    WHEN "SB" THEN 4
                    WHEN "B" THEN 3
                    WHEN "C" THEN 2
                    WHEN "K" THEN 1
                    ELSE 0
                END) as avg_manfaat,
                AVG((
                    CASE penguasaan_trainer WHEN "SB" THEN 4 WHEN "B" THEN 3 WHEN "C" THEN 2 WHEN "K" THEN 1 ELSE 0 END +
                    CASE penyampaian_trainer WHEN "SB" THEN 4 WHEN "B" THEN 3 WHEN "C" THEN 2 WHEN "K" THEN 1 ELSE 0 END +
                    CASE pemahaman_materi WHEN "SB" THEN 4 WHEN "B" THEN 3 WHEN "C" THEN 2 WHEN "K" THEN 1 ELSE 0 END +
                    CASE manfaat_keseluruhan WHEN "SB" THEN 4 WHEN "B" THEN 3 WHEN "C" THEN 2 WHEN "K" THEN 1 ELSE 0 END
                ) / 4) as avg_total
            ')
            ->first();

        return view('trainers.performance', compact('trainer', 'evaluasiStats'));
    }

    /**
     * Toggle trainer status
     */
    public function toggleStatus(Trainer $trainer)
    {
        $trainer->update(['status' => !$trainer->status]);

        return redirect()->back()
            ->with('success', 'Status trainer berhasil diubah!');
    }
}