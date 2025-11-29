@extends('layouts.app')

@section('page-title', 'Hasil Evaluasi Trainer')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Hasil Evaluasi Trainer</h1>
            <p class="text-gray-600 mt-2">{{ $training->materiTraining->nama_materi ?? '-' }}</p>
        </div>
        <a href="{{ route('evaluasi-trainer.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Training Info Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Informasi Training</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-600">Materi</p>
                <p class="font-semibold">{{ $training->materiTraining->nama_materi ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal</p>
                <p class="font-semibold">
                    {{ $training->tanggal_training->format('d F Y') }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Trainer</p>
                <p class="font-semibold">{{ $training->trainer->nama_trainer ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Evaluasi</p>
                <p class="font-semibold">{{ $evaluasi->count() }} responden</p>
            </div>
        </div>
    </div>

    <!-- Statistics Charts -->
    @if($evaluasi->isNotEmpty())
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Statistik Evaluasi</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($statistik as $aspect => $stats)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">
                            {{ $aspectLabels[$aspect] }}
                        </h4>

                        <div class="space-y-2">
                            @foreach(['SB' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup', 'K' => 'Kurang'] as $indicator => $label)
                                @php
                                    $percentage = $stats[$indicator]['percentage'];
                                    $count = $stats[$indicator]['count'];
                                    $colorClass = [
                                        'SB' => 'bg-green-500',
                                        'B' => 'bg-blue-500',
                                        'C' => 'bg-yellow-500',
                                        'K' => 'bg-red-500'
                                    ][$indicator];
                                @endphp
                                
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs font-medium text-gray-600">{{ $label }}</span>
                                        <span class="text-xs font-semibold text-gray-700">{{ $percentage }}%</span>
                                    </div>

                                    <div class="w-full bg-gray-200 rounded-full h-6 relative">
                                        <div class="{{ $colorClass }} h-6 rounded-full flex items-center justify-end pr-2"
                                             style="width: {{ $percentage }}%">
                                            @if($percentage > 15)
                                                <span class="text-xs font-semibold text-white">{{ $count }}</span>
                                            @endif
                                        </div>

                                        @if($percentage <= 15 && $count > 0)
                                            <span class="absolute right-2 top-0.5 text-xs font-semibold text-gray-700">{{ $count }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Detail Evaluasi per Peserta -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Detail Evaluasi per Peserta</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peserta</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Relevansi</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Pemahaman</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Penguasaan</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Penyampaian</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Fasilitas</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Manfaat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($evaluasi as $index => $eval)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-medium">
                                    {{ $eval->peserta->employee->name ?? '-' }}
                                </td>

                                @foreach([
                                    'relevansi_materi',
                                    'pemahaman_materi',
                                    'penguasaan_trainer',
                                    'penyampaian_trainer',
                                    'fasilitas',
                                    'manfaat_keseluruhan'
                                ] as $field)
                                    @php
                                        $value = $eval->$field;
                                        $color = match($value) {
                                            'SB' => 'bg-green-100 text-green-800',
                                            'B'  => 'bg-blue-100 text-blue-800',
                                            'C'  => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-red-100 text-red-800',
                                        };
                                    @endphp
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded {{ $color }}">
                                            {{ $value }}
                                        </span>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-chart-bar text-gray-400 text-5xl mb-4"></i>
            <p class="text-gray-500 text-lg">Belum ada evaluasi untuk training ini</p>
            <p class="text-gray-400 text-sm mt-2">Evaluasi akan muncul setelah peserta memberikan penilaian</p>
        </div>
    @endif
</div>
@endsection
