@extends('layouts.app')

@section('page-title', 'Edit Evaluasi Trainer')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Training Info Card -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">{{ $evaluasi->training->materiTraining->nama_materi ?? 'Training' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-blue-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-blue-200">Tanggal</p>
                            <p class="font-semibold">{{ $evaluasi->training->tanggal_training->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-chalkboard-teacher text-blue-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-blue-200">Trainer</p>
                            <p class="font-semibold">{{ $evaluasi->training->trainer->nama_trainer ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-user text-blue-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-blue-200">Peserta</p>
                            <p class="font-semibold">{{ $evaluasi->peserta->employee->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('evaluasi-trainer.show', $evaluasi->training_id) }}" 
               class="ml-4 px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Peserta Info -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-user text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Mengedit evaluasi untuk:</p>
                <p class="text-lg font-semibold text-gray-900">{{ $evaluasi->peserta->employee->name ?? '-' }}</p>
                <p class="text-sm text-gray-600">
                    {{ $evaluasi->peserta->employee->department->name ?? '-' }} • 
                    {{ $evaluasi->peserta->employee->jobLevel->name ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Form Edit -->
    <form action="{{ route('evaluasi-trainer.update', $evaluasi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Edit Penilaian Trainer</h3>
                <p class="text-sm text-gray-600 mt-1">Perbarui penilaian evaluasi trainer</p>
            </div>

            <div class="p-6 space-y-6">
                
                <!-- Aspek Penilaian -->
                <div class="space-y-6">
                    
                    @foreach([
                        'relevansi_materi' => 'Relevansi Materi dengan Kebutuhan Kerja',
                        'pemahaman_materi' => 'Kemudahan Pemahaman Materi',
                        'penguasaan_trainer' => 'Penguasaan Materi oleh Trainer',
                        'penyampaian_trainer' => 'Metode Penyampaian Trainer',
                        'fasilitas' => 'Fasilitas dan Sarana Training',
                        'manfaat_keseluruhan' => 'Manfaat Keseluruhan Training'
                    ] as $field => $label)
                        <div class="border rounded-lg p-4 hover:border-blue-300 transition">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                {{ $loop->iteration }}. {{ $label }}
                                <span class="text-red-500">*</span>
                            </label>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($indikatorOptions as $value => $desc)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" 
                                               name="{{ $field }}" 
                                               value="{{ $value }}"
                                               {{ $evaluasi->$field == $value ? 'checked' : '' }}
                                               class="peer sr-only"
                                               required>
                                        <div class="border-2 border-gray-200 rounded-lg p-4 text-center transition-all
                                                    peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-md
                                                    hover:border-blue-300 hover:shadow">
                                            <div class="text-2xl font-bold mb-1
                                                {{ $value == 'SB' ? 'text-green-600' : '' }}
                                                {{ $value == 'B' ? 'text-blue-600' : '' }}
                                                {{ $value == 'C' ? 'text-yellow-600' : '' }}
                                                {{ $value == 'K' ? 'text-red-600' : '' }}">
                                                {{ $value }}
                                            </div>
                                            <div class="text-xs text-gray-600">{{ $desc }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            
                            @error($field)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                </div>

                <!-- Keterangan Penilaian -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h5 class="text-sm font-semibold text-blue-900 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>Keterangan Penilaian:
                    </h5>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                        <div class="flex items-center">
                            <span class="font-bold text-green-600 mr-2">SB</span>
                            <span class="text-gray-700">= Sangat Baik</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-bold text-blue-600 mr-2">B</span>
                            <span class="text-gray-700">= Baik</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-bold text-yellow-600 mr-2">C</span>
                            <span class="text-gray-700">= Cukup</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-bold text-red-600 mr-2">K</span>
                            <span class="text-gray-700">= Kurang</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
                <a href="{{ route('evaluasi-trainer.show', $evaluasi->training_id) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-700 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    <i class="fas fa-save mr-2"></i>Update Evaluasi
                </button>
            </div>
        </div>
    </form>

</div>
@endsection