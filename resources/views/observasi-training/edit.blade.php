@extends('layouts.app')

@section('page-title', 'Edit Observasi Training')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Training Info Card -->
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">{{ $observasi->training->materiTraining->nama_materi ?? 'Training' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-yellow-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-yellow-200">Tanggal</p>
                            <p class="font-semibold">{{ $observasi->tanggal_training->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-chalkboard-teacher text-yellow-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-yellow-200">Trainer</p>
                            <p class="font-semibold">{{ $observasi->trainer->nama_trainer ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-user text-yellow-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-yellow-200">Observer</p>
                            <p class="font-semibold">{{ $observasi->user->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('observasi-training.show', $observasi->training_id) }}" 
               class="ml-4 px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form Edit Observasi -->
    <form action="{{ route('observasi-training.update', $observasi->training_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Edit Observasi Training</h3>
                <p class="text-sm text-gray-600 mt-1">Perbarui penilaian untuk setiap aspek pelaksanaan training</p>
            </div>

            <div class="p-6 space-y-6">
                
                <!-- Keterangan Penilaian -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h5 class="text-sm font-semibold text-yellow-900 mb-3">
                        <i class="fas fa-info-circle mr-1"></i>Keterangan Penilaian:
                    </h5>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center bg-white px-3 py-2 rounded-lg">
                            <span class="font-bold text-green-600 mr-2">Ada</span>
                            <span class="text-gray-700">= Aspek ini dilaksanakan</span>
                        </div>
                        <div class="flex items-center bg-white px-3 py-2 rounded-lg">
                            <span class="font-bold text-red-600 mr-2">Tidak</span>
                            <span class="text-gray-700">= Aspek ini tidak dilaksanakan</span>
                        </div>
                    </div>
                </div>

                <!-- Aspek Penilaian -->
                <div class="space-y-5">
                    @foreach($aspekPenilaian as $field => $label)
                        <div class="border rounded-lg p-4 hover:border-yellow-300 transition">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                {{ $loop->iteration }}. {{ $label }}
                                <span class="text-red-500">*</span>
                            </label>
                            
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($penilaianOptions as $value => $desc)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" 
                                               name="{{ $field }}" 
                                               value="{{ $value }}"
                                               {{ old($field, $observasi->$field) == $value ? 'checked' : '' }}
                                               class="peer sr-only"
                                               required>
                                        <div class="border-2 border-gray-300 rounded-lg p-4 text-center transition-all
                                                    peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:shadow-lg peer-checked:scale-105
                                                    hover:border-yellow-400 hover:shadow">
                                            <div class="text-2xl font-bold mb-1
                                                {{ $value == 'Ada' ? 'text-green-600' : 'text-red-600' }}">
                                                <i class="fas {{ $value == 'Ada' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            </div>
                                            <div class="text-sm font-semibold text-gray-700">{{ $desc }}</div>
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

                <!-- Catatan -->
                <div class="border rounded-lg p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-sticky-note mr-1"></i>Catatan Tambahan (Opsional)
                    </label>
                    <textarea name="catatan" 
                              rows="4" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                              placeholder="Tambahkan catatan atau observasi khusus lainnya...">{{ old('catatan', $observasi->catatan) }}</textarea>
                    @error('catatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Update -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Informasi:</p>
                            <p>Observasi dibuat pada: <strong>{{ $observasi->created_at->format('d F Y, H:i') }}</strong></p>
                            @if($observasi->updated_at != $observasi->created_at)
                                <p>Terakhir diupdate: <strong>{{ $observasi->updated_at->format('d F Y, H:i') }}</strong></p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
                <a href="{{ route('observasi-training.show', $observasi->training_id) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-700 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white rounded-lg font-semibold transition shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Update Observasi
                </button>
            </div>
        </div>
    </form>

</div>
@endsection
