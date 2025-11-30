@extends('layouts.app')

@section('page-title', 'Detail Evaluasi Atasan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <a href="{{ route('evaluasi-atasan.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Detail Evaluasi Atasan</h1>
            </div>
            <div class="flex space-x-2">
                @if($evaluasiAtasan->atasan_id == Auth::id())
                    <a href="{{ route('evaluasi-atasan.edit', $evaluasiAtasan) }}" 
                       class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endif
                <a href="{{ route('evaluasi-atasan.print', $evaluasiAtasan) }}" 
                   target="_blank"
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-print mr-2"></i>Print
                </a>
            </div>
        </div>
    </div>

    <!-- Kategori Badge -->
    <div class="mb-6">
        <span class="px-6 py-3 rounded-full text-lg font-bold
            @if($evaluasiAtasan->kategori == 'Sangat Baik') bg-green-100 text-green-800
            @elseif($evaluasiAtasan->kategori == 'Baik') bg-blue-100 text-blue-800
            @elseif($evaluasiAtasan->kategori == 'Cukup') bg-yellow-100 text-yellow-800
            @else bg-red-100 text-red-800
            @endif">
            {{ $evaluasiAtasan->kategori }} ({{ $evaluasiAtasan->total_skor }}/20)
        </span>
    </div>

    <!-- Info Karyawan & Training -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Info Karyawan -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-user text-blue-600 mr-2"></i>
                Informasi Karyawan
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Nama Karyawan</p>
                    <p class="font-semibold text-gray-900">{{ $evaluasiAtasan->nama_karyawan }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Department</p>
                    <p class="font-semibold text-gray-900">{{ $evaluasiAtasan->department }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Job Level</p>
                    <p class="font-semibold text-gray-900">{{ $evaluasiAtasan->peserta->employee->jobLevel->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Info Training -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-chalkboard-teacher text-green-600 mr-2"></i>
                Informasi Training
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Materi Training</p>
                    <p class="font-semibold text-gray-900">{{ $evaluasiAtasan->materi_training }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Training</p>
                    <p class="font-semibold text-gray-900">{{ $evaluasiAtasan->tanggal_training->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Trainer</p>
                    <p class="font-semibold text-gray-900">{{ $evaluasiAtasan->training->trainer->nama_trainer ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Evaluator -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-user-tie text-purple-600 mr-2"></i>
            Informasi Evaluator
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nama Atasan</p>
                <p class="font-semibold text-gray-900">{{ $evaluasiAtasan->atasan->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Department</p>
                <p class="font-semibold text-gray-900">{{ $evaluasiAtasan->atasan->department->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Evaluasi</p>
                <p class="font-semibold text-gray-900">{{ $evaluasiAtasan->created_at->format('d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Detail Penilaian -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
            <h3 class="text-lg font-bold text-white">Detail Penilaian Aspek</h3>
        </div>
        <div class="p-6">
            <!-- Aspek 1 -->
            <div class="mb-6 pb-6 border-b">
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-semibold text-gray-900">1. Peningkatan Keterampilan</h4>
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-blue-600 mr-2">{{ $evaluasiAtasan->peningkatan_keterampilan }}</span>
                        <span class="text-sm text-gray-600">/ 5</span>
                    </div>
                </div>
                <div class="bg-gray-100 rounded px-4 py-3">
                    <p class="text-sm text-gray-700">{{ $evaluasiAtasan->uraian_peningkatan_keterampilan ?: 'Tidak ada uraian' }}</p>
                </div>
            </div>

            <!-- Aspek 2 -->
            <div class="mb-6 pb-6 border-b">
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-semibold text-gray-900">2. Penerapan Ilmu/Pengetahuan</h4>
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-blue-600 mr-2">{{ $evaluasiAtasan->penerapan_ilmu }}</span>
                        <span class="text-sm text-gray-600">/ 5</span>
                    </div>
                </div>
                <div class="bg-gray-100 rounded px-4 py-3">
                    <p class="text-sm text-gray-700">{{ $evaluasiAtasan->uraian_penerapan_ilmu ?: 'Tidak ada uraian' }}</p>
                </div>
            </div>

            <!-- Aspek 3 -->
            <div class="mb-6 pb-6 border-b">
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-semibold text-gray-900">3. Perubahan Perilaku Kerja</h4>
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-blue-600 mr-2">{{ $evaluasiAtasan->perubahan_perilaku }}</span>
                        <span class="text-sm text-gray-600">/ 5</span>
                    </div>
                </div>
                <div class="bg-gray-100 rounded px-4 py-3">
                    <p class="text-sm text-gray-700">{{ $evaluasiAtasan->uraian_perubahan_perilaku ?: 'Tidak ada uraian' }}</p>
                </div>
            </div>

            <!-- Aspek 4 -->
            <div class="mb-6 pb-6 border-b">
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-semibold text-gray-900">4. Dampak pada Performa/Hasil Kerja</h4>
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-blue-600 mr-2">{{ $evaluasiAtasan->dampak_performa }}</span>
                        <span class="text-sm text-gray-600">/ 5</span>
                    </div>
                </div>
                <div class="bg-gray-100 rounded px-4 py-3">
                    <p class="text-sm text-gray-700">{{ $evaluasiAtasan->uraian_dampak_performa ?: 'Tidak ada uraian' }}</p>
                </div>
            </div>

            <!-- Total Skor -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Total Skor</h4>
                        <p class="text-sm text-gray-600">Akumulasi dari 4 aspek penilaian</p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold text-blue-600">{{ $evaluasiAtasan->total_skor }}</div>
                        <div class="text-sm text-gray-600">dari 20</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Catatan Atasan -->
    @if($evaluasiAtasan->catatan_atasan)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-comment-dots text-orange-600 mr-2"></i>
            Catatan/Saran Atasan
        </h3>
        <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded">
            <p class="text-gray-700">{{ $evaluasiAtasan->catatan_atasan }}</p>
        </div>
    </div>
    @endif

    <!-- Statistik Visual -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Visualisasi Penilaian</h3>
        <div class="space-y-4">
            @php
                $aspects = [
                    ['label' => 'Peningkatan Keterampilan', 'score' => $evaluasiAtasan->peningkatan_keterampilan],
                    ['label' => 'Penerapan Ilmu', 'score' => $evaluasiAtasan->penerapan_ilmu],
                    ['label' => 'Perubahan Perilaku', 'score' => $evaluasiAtasan->perubahan_perilaku],
                    ['label' => 'Dampak Performa', 'score' => $evaluasiAtasan->dampak_performa],
                ];
            @endphp
            
            @foreach($aspects as $aspect)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $aspect['label'] }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $aspect['score'] }}/5</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-blue-600 h-4 rounded-full transition-all duration-300" 
                             style="width: {{ ($aspect['score'] / 5) * 100 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endsection
