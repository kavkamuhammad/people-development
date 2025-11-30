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

    <!-- Tabs -->
    <div class="mb-6" x-data="{ activeTab: 'belum' }">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'belum'"
                        :class="activeTab === 'belum' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Belum Evaluasi
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs"
                          :class="activeTab === 'belum' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600'">
                        {{ $karyawanPerluEvaluasi->total() }}
                    </span>
                </button>
                <button @click="activeTab = 'sudah'"
                        :class="activeTab === 'sudah' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Sudah Evaluasi
                    <span class="ml-2 py-0.5 px-2 rounded-full text-xs"
                          :class="activeTab === 'sudah' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600'">
                        {{ $evaluasiSaya->count() }}
                    </span>
                </button>
            </nav>
        </div>

        <!-- Tab Content: Belum Evaluasi -->
        <div x-show="activeTab === 'belum'" class="mt-6">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materi Training</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trainer</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Peserta (Dept)</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($karyawanPerluEvaluasi as $peserta)
                                @php
                                    $training = $peserta->training;
                                    $employee = $peserta->employee;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $training->tanggal_training->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $training->materiTraining->nama_materi ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $training->trainer->nama_trainer ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center text-gray-900">
                                        <div>{{ $employee->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $employee->department->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Belum
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('evaluasi-atasan.create', $training->id) }}" 
                                           class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                            <i class="fas fa-plus mr-1"></i>
                                            Beri Evaluasi
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-check-circle text-green-500 text-5xl mb-4"></i>
                                            <p class="text-gray-500 text-lg">Semua peserta sudah dievaluasi</p>
                                            <p class="text-gray-400 text-sm mt-2">Tidak ada yang perlu dievaluasi saat ini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t">
                    {{ $karyawanPerluEvaluasi->links() }}
                </div>
            </div>
        </div>

        <!-- Tab Content: Sudah Evaluasi -->
        <div x-show="activeTab === 'sudah'" class="mt-6">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Training</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materi Training</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Skor</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($evaluasiSaya as $evaluasi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $evaluasi->tanggal_training->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $evaluasi->materi_training }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div>{{ $evaluasi->nama_karyawan }}</div>
                                        <div class="text-xs text-gray-500">{{ $evaluasi->department }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-lg font-bold text-purple-600">{{ $evaluasi->total_skor }}</span>
                                        <span class="text-sm text-gray-600">/20</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            @if($evaluasi->kategori == 'Sangat Baik') bg-green-100 text-green-800
                                            @elseif($evaluasi->kategori == 'Baik') bg-blue-100 text-blue-800
                                            @elseif($evaluasi->kategori == 'Cukup') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $evaluasi->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('evaluasi-atasan.show', $evaluasi) }}" 
                                               class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </a>
                                            <a href="{{ route('evaluasi-atasan.edit', $evaluasi) }}" 
                                               class="px-3 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                            <p class="text-gray-500 text-lg">Belum ada evaluasi</p>
                                            <p class="text-gray-400 text-sm mt-2">Evaluasi yang Anda buat akan muncul di sini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
                        <li>Total skor akan dihitung otomatis dan dikategorikan: Sangat Baik (17-20), Baik (13-16), Cukup (9-12), Perlu Perbaikan (&lt;9)</li>
                        <li>Form evaluasi dapat di-print untuk dokumentasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection