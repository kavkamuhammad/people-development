<?php

namespace App\Http\Controllers;

use App\Models\MateriTraining;
use Illuminate\Http\Request;

class MateriTrainingController extends Controller
{
    public function index()
    {
        $materiTrainings = MateriTraining::orderBy('kode_materi')->get();
        return view('materi-trainings.index', compact('materiTrainings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_materi' => 'required|string|unique:materi_trainings,kode_materi',
            'nama_materi' => 'required|string',
            'jenis_materi' => 'required|string',
        ]);

        MateriTraining::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Materi training berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, MateriTraining $materiTraining)
    {
        $validated = $request->validate([
            'kode_materi' => 'required|string|unique:materi_trainings,kode_materi,' . $materiTraining->id,
            'nama_materi' => 'required|string',
            'jenis_materi' => 'required|string',
        ]);

        $materiTraining->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Materi training berhasil diupdate'
        ]);
    }

    public function destroy(MateriTraining $materiTraining)
    {
        $materiTraining->delete();

        return response()->json([
            'success' => true,
            'message' => 'Materi training berhasil dihapus'
        ]);
    }
}