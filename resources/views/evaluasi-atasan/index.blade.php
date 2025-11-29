@extends('layouts.app')

@section('page-title', 'Evaluasi Atasan Langsung')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Evaluasi Atasan Langsung</h1>
            <p class="text-gray-600 mt-2">Evaluasi penerapan hasil training oleh karyawan</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            {{ session('info') }}
        </div>
    @endif

    <!-- Training List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materi Training</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trainer</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Peserta (Dept)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Evaluasi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($trainings as $training)
                        @php
                            $user = auth()->user();
                            $pesertaDept = $training->peserta->filter(function($p) use ($user) {
                                return $p->karyawan->departement == $user->departement;
                            });
                            $totalEvaluasi = \App\Models\EvaluasiAtasan::where('training_id', $training->id)
                                ->where('atasan_id', $user->id)
                                ->count();
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $training->tanggal->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $training->materi }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $training->trainer->nama }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-900">
                                {{ $pesertaDept->count() }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($totalEvaluasi > 0)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $totalEvaluasi }} evaluasi
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Belum ada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('evaluasi-atasan.create', $training) }}" 
                                       class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                        <i class="fas fa-plus mr-1"></i>
                                        Beri Evaluasi
                                    </a>
                                    @if($totalEvaluasi > 0)
                                        <a href="{{ route('evaluasi-atasan.show', $training) }}" 
                                           class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                            <i class="fas fa-eye mr-1"></i>
                                            Lihat
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">Belum ada training yang tersedia</p>
                                    <p class="text-gray-400 text-sm mt-2">Training dengan peserta dari departemen Anda akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($trainings->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $trainings->links() }}
            </div>
        @endif
    </div>

    <!-- Info Card -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informasi Evaluasi Atasan</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Evaluasi dilakukan setelah karyawan menerapkan hasil training (minimal 1 bulan setelah training)</li>
                        <li>Penilaian mencakup 4 aspek dengan skor 1-5 dan uraian/contoh untuk setiap aspek</li>
                        <li>Total skor akan dihitung otomatis dan dikategorikan: Sangat Baik (17-20), Baik (13-16), Cukup (9-12), Perlu Perbaikan (<9)</li>
                        <li>Form evaluasi dapat di-print untuk dokumentasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection