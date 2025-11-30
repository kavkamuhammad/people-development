@extends('layouts.app')

@section('page-title', 'Training Record Karyawan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Training Record Karyawan</h1>
        <p class="text-gray-600 mt-2">Rekap hasil training dan evaluasi karyawan</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('training-record.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Select Training -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-chalkboard-teacher mr-2 text-blue-600"></i>
                        Pilih Training
                    </label>
                    <select name="training_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            onchange="this.form.submit()">
                        <option value="">-- Pilih Training --</option>
                        @foreach($trainings as $training)
                            <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                                {{ $training->materiTraining->nama_materi ?? '-' }} - 
                                {{ $training->tanggal_training->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($selectedTraining)
                <!-- Search -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-search mr-2 text-blue-600"></i>
                        Cari Nama Karyawan
                    </label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari nama karyawan..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-filter mr-2 text-blue-600"></i>
                        Filter Status
                    </label>
                    <select name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="Tidak Lulus" {{ request('status') == 'Tidak Lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                    </select>
                </div>
                @endif
            </div>

            @if($selectedTraining)
            <div class="flex justify-between items-center">
                <div class="flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('training-record.index', ['training_id' => request('training_id')]) }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
                <a href="{{ route('training-record.export-excel', ['training_id' => request('training_id'), 'search' => request('search'), 'status' => request('status')]) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
            </div>
            @endif
        </form>
    </div>

    @if($selectedTraining)
    <!-- Training Info -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white">
        <h2 class="text-xl font-bold mb-4">Informasi Training</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-blue-100 text-sm">Materi Training</p>
                <p class="font-semibold text-lg">{{ $selectedTraining->materiTraining->nama_materi ?? '-' }}</p>
            </div>
            <div>
                <p class="text-blue-100 text-sm">Trainer</p>
                <p class="font-semibold text-lg">{{ $selectedTraining->trainer->nama_trainer ?? '-' }}</p>
            </div>
            <div>
                <p class="text-blue-100 text-sm">Tanggal Training</p>
                <p class="font-semibold text-lg">{{ $selectedTraining->tanggal_training->format('d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Peserta</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pesertaData->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Lulus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pesertaData->where('status', 'Lulus')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Tidak Lulus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pesertaData->where('status', 'Tidak Lulus')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Rata-rata Point</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($pesertaData->avg('total_point'), 1) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Karyawan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Departemen</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Pemahaman Peserta</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Skor Evaluasi Atasan</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Point</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pesertaData as $index => $peserta)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-4 py-4">
                                <div class="font-semibold text-gray-900">{{ $peserta['nama_karyawan'] }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $peserta['department'] }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="text-lg font-bold text-blue-600">{{ $peserta['pemahaman_peserta'] }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($peserta['has_evaluasi'])
                                    <span class="text-lg font-bold text-purple-600">{{ $peserta['skor_evaluasi'] }}</span>
                                @else
                                    <span class="text-sm text-gray-400 italic">Belum evaluasi</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-bold
                                        {{ $peserta['total_point'] >= 90 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $peserta['total_point'] }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $peserta['skor_evaluasi'] < 13 ? '(Posttest - Evaluasi)' : '(Posttest + Evaluasi)' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $peserta['status'] === 'Lulus' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $peserta['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">
                                <i class="fas fa-{{ $peserta['status'] === 'Lulus' ? 'check' : 'exclamation' }}-circle mr-1"></i>
                                {{ $peserta['catatan'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                <p class="text-gray-500">Tidak ada data peserta untuk training ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Card -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Keterangan Perhitungan</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>Pemahaman Peserta:</strong> Nilai dari skor posttest</li>
                        <li><strong>Total Point:</strong> Jika skor evaluasi atasan &lt; 13, maka Total = Posttest - Evaluasi. Jika ≥ 13, maka Total = Posttest + Evaluasi</li>
                        <li><strong>Status Lulus:</strong> Total Point ≥ 90</li>
                        <li><strong>Status Tidak Lulus:</strong> Total Point &lt; 90</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Pilih Training</h3>
        <p class="text-gray-600">Silakan pilih training dari dropdown di atas untuk melihat training record karyawan</p>
    </div>
    @endif
</div>
@endsection
