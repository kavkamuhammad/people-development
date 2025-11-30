@extends('layouts.app')

@section('page-title', 'Edit Evaluasi Atasan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('evaluasi-atasan.show', $evaluasiAtasan) }}" class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Evaluasi Atasan</h1>
        </div>
        <p class="text-gray-600">Perbarui evaluasi penerapan hasil training</p>
    </div>

    <!-- Info Training Card -->
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-700 rounded-lg shadow-lg p-6 mb-6 text-white">
        <h2 class="text-xl font-bold mb-4">Informasi Training</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-yellow-100 text-sm mb-1">Karyawan</p>
                <p class="font-semibold text-lg">{{ $evaluasiAtasan->nama_karyawan }}</p>
            </div>
            <div>
                <p class="text-yellow-100 text-sm mb-1">Department</p>
                <p class="font-semibold">{{ $evaluasiAtasan->department }}</p>
            </div>
            <div>
                <p class="text-yellow-100 text-sm mb-1">Materi Training</p>
                <p class="font-semibold">{{ $evaluasiAtasan->materi_training }}</p>
            </div>
            <div>
                <p class="text-yellow-100 text-sm mb-1">Tanggal Training</p>
                <p class="font-semibold">{{ $evaluasiAtasan->tanggal_training->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Form Edit Evaluasi -->
    <form action="{{ route('evaluasi-atasan.update', $evaluasiAtasan) }}" method="POST" class="bg-white rounded-lg shadow-lg p-6">
        @csrf
        @method('PUT')

        <h3 class="text-xl font-bold text-gray-900 mb-6">Penilaian Aspek Training</h3>

        <!-- Aspek 1: Peningkatan Keterampilan -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <label class="block text-sm font-semibold text-gray-900 mb-3">
                1. Peningkatan keterampilan setelah mengikuti training
                <span class="text-red-500">*</span>
            </label>
            
            <div class="grid grid-cols-5 gap-2 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <label class="flex flex-col items-center cursor-pointer">
                        <input type="radio" name="peningkatan_keterampilan" value="{{ $i }}" 
                               class="mb-2" required 
                               {{ old('peningkatan_keterampilan', $evaluasiAtasan->peningkatan_keterampilan) == $i ? 'checked' : '' }}>
                        <span class="text-xs text-center font-medium">{{ $i }}</span>
                        <span class="text-xs text-center text-gray-600">
                            @if($i == 1) Sangat Kurang
                            @elseif($i == 2) Kurang
                            @elseif($i == 3) Cukup
                            @elseif($i == 4) Baik
                            @else Sangat Baik
                            @endif
                        </span>
                    </label>
                @endfor
            </div>

            <label class="block text-sm text-gray-700 mb-2">
                Uraian/Contoh Konkret:
            </label>
            <textarea name="uraian_keterampilan" rows="3" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                      placeholder="Berikan contoh konkret peningkatan keterampilan yang terlihat...">{{ old('uraian_keterampilan', $evaluasiAtasan->uraian_peningkatan_keterampilan) }}</textarea>
            @error('peningkatan_keterampilan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Aspek 2: Penerapan Pengetahuan -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <label class="block text-sm font-semibold text-gray-900 mb-3">
                2. Penerapan ilmu/pengetahuan training dalam pekerjaan sehari-hari
                <span class="text-red-500">*</span>
            </label>
            
            <div class="grid grid-cols-5 gap-2 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <label class="flex flex-col items-center cursor-pointer">
                        <input type="radio" name="penerapan_pengetahuan" value="{{ $i }}" 
                               class="mb-2" required 
                               {{ old('penerapan_pengetahuan', $evaluasiAtasan->penerapan_ilmu) == $i ? 'checked' : '' }}>
                        <span class="text-xs text-center font-medium">{{ $i }}</span>
                        <span class="text-xs text-center text-gray-600">
                            @if($i == 1) Sangat Kurang
                            @elseif($i == 2) Kurang
                            @elseif($i == 3) Cukup
                            @elseif($i == 4) Baik
                            @else Sangat Baik
                            @endif
                        </span>
                    </label>
                @endfor
            </div>

            <label class="block text-sm text-gray-700 mb-2">
                Uraian/Contoh Konkret:
            </label>
            <textarea name="uraian_penerapan" rows="3" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                      placeholder="Berikan contoh konkret penerapan ilmu dalam pekerjaan...">{{ old('uraian_penerapan', $evaluasiAtasan->uraian_penerapan_ilmu) }}</textarea>
            @error('penerapan_pengetahuan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Aspek 3: Perubahan Perilaku -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <label class="block text-sm font-semibold text-gray-900 mb-3">
                3. Perubahan perilaku kerja (inisiatif, disiplin, kerja sama, kepatuhan SOP)
                <span class="text-red-500">*</span>
            </label>
            
            <div class="grid grid-cols-5 gap-2 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <label class="flex flex-col items-center cursor-pointer">
                        <input type="radio" name="perubahan_perilaku" value="{{ $i }}" 
                               class="mb-2" required 
                               {{ old('perubahan_perilaku', $evaluasiAtasan->perubahan_perilaku) == $i ? 'checked' : '' }}>
                        <span class="text-xs text-center font-medium">{{ $i }}</span>
                        <span class="text-xs text-center text-gray-600">
                            @if($i == 1) Sangat Kurang
                            @elseif($i == 2) Kurang
                            @elseif($i == 3) Cukup
                            @elseif($i == 4) Baik
                            @else Sangat Baik
                            @endif
                        </span>
                    </label>
                @endfor
            </div>

            <label class="block text-sm text-gray-700 mb-2">
                Uraian/Contoh Konkret:
            </label>
            <textarea name="uraian_perilaku" rows="3" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                      placeholder="Berikan contoh konkret perubahan perilaku yang terlihat...">{{ old('uraian_perilaku', $evaluasiAtasan->uraian_perubahan_perilaku) }}</textarea>
            @error('perubahan_perilaku')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Aspek 4: Dampak Performa -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <label class="block text-sm font-semibold text-gray-900 mb-3">
                4. Dampak pada performa/hasil kerja (efisiensi, kualitas, produktivitas)
                <span class="text-red-500">*</span>
            </label>
            
            <div class="grid grid-cols-5 gap-2 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <label class="flex flex-col items-center cursor-pointer">
                        <input type="radio" name="dampak_performa" value="{{ $i }}" 
                               class="mb-2" required 
                               {{ old('dampak_performa', $evaluasiAtasan->dampak_performa) == $i ? 'checked' : '' }}>
                        <span class="text-xs text-center font-medium">{{ $i }}</span>
                        <span class="text-xs text-center text-gray-600">
                            @if($i == 1) Sangat Kurang
                            @elseif($i == 2) Kurang
                            @elseif($i == 3) Cukup
                            @elseif($i == 4) Baik
                            @else Sangat Baik
                            @endif
                        </span>
                    </label>
                @endfor
            </div>

            <label class="block text-sm text-gray-700 mb-2">
                Uraian/Contoh Konkret:
            </label>
            <textarea name="uraian_performa" rows="3" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                      placeholder="Berikan contoh konkret dampak pada performa kerja...">{{ old('uraian_performa', $evaluasiAtasan->uraian_dampak_performa) }}</textarea>
            @error('dampak_performa')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Catatan Atasan -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-900 mb-2">
                Catatan/Saran untuk Pengembangan Lebih Lanjut
            </label>
            <textarea name="catatan_atasan" rows="4" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                      placeholder="Berikan catatan atau saran untuk pengembangan karyawan lebih lanjut...">{{ old('catatan_atasan', $evaluasiAtasan->catatan_atasan) }}</textarea>
        </div>

        <!-- Info Box -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <i class="fas fa-info-circle text-yellow-600 mr-3 mt-1"></i>
                <div class="text-sm text-yellow-800">
                    <p class="font-semibold mb-1">Panduan Penilaian:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Total Skor akan dihitung otomatis dari 4 aspek penilaian</li>
                        <li>Berikan uraian yang spesifik dan konkret untuk setiap aspek</li>
                        <li>Kategori hasil: Sangat Baik (17-20), Baik (13-16), Cukup (9-12), Perlu Perbaikan (&lt;9)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('evaluasi-atasan.show', $evaluasiAtasan) }}" 
               class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                <i class="fas fa-save mr-2"></i>Update Evaluasi
            </button>
        </div>
    </form>
</div>
@endsection
