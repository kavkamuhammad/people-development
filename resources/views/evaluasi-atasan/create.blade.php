@extends('layouts.app')

@section('page-title', 'Evaluasi Atasan Langsung')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    
    <!-- Training Info Card -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">{{ $training->materiTraining->nama_materi ?? 'Training' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-purple-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-purple-200">Tanggal</p>
                            <p class="font-semibold">{{ $training->tanggal_training->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-chalkboard-teacher text-purple-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-purple-200">Trainer</p>
                            <p class="font-semibold">{{ $training->trainer->nama_trainer ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-users text-purple-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-purple-200">Peserta dari Dept Anda</p>
                            <p class="font-semibold">{{ $pesertaBelumEvaluasi->count() + $pesertaSudahEvaluasi->count() }} Orang</p>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('evaluasi-atasan.index') }}" 
               class="ml-4 px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Progress Info -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Sudah Evaluasi: 
                        <strong class="text-gray-900">{{ $pesertaSudahEvaluasi->count() }}</strong>
                    </span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                    <span class="text-sm text-gray-600">Belum Evaluasi: 
                        <strong class="text-gray-900">{{ $pesertaBelumEvaluasi->count() }}</strong>
                    </span>
                </div>
            </div>
            @if($pesertaBelumEvaluasi->count() > 0)
                <span class="text-sm text-orange-600 font-medium">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Masih ada {{ $pesertaBelumEvaluasi->count() }} peserta yang belum dievaluasi
                </span>
            @else
                <span class="text-sm text-green-600 font-medium">
                    <i class="fas fa-check-circle mr-1"></i>
                    Semua peserta sudah dievaluasi
                </span>
            @endif
        </div>
    </div>

    @if($pesertaBelumEvaluasi->count() > 0)
    <!-- Form Evaluasi -->
    <form action="{{ route('evaluasi-atasan.store', $training) }}" method="POST" x-data="evaluasiForm()" @submit="validateForm">
        @csrf
        <input type="hidden" name="training_id" value="{{ $training->id }}">

        <!-- Step 1: Pilih Peserta -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-purple-50 to-purple-100">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                        1
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Pilih Peserta</h3>
                        <p class="text-sm text-gray-600">Centang peserta yang akan dievaluasi</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @error('evaluasi')
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ $message }}
                    </div>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pesertaBelumEvaluasi as $peserta)
                        <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-purple-400 hover:bg-purple-50"
                               :class="selectedPeserta.includes({{ $peserta->id }}) ? 'border-purple-600 bg-purple-50' : 'border-gray-200'">
                            <input type="checkbox" 
                                   x-model="selectedPeserta" 
                                   value="{{ $peserta->id }}"
                                   class="mt-1 mr-3 h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">{{ $peserta->employee->name }}</div>
                                <div class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-building text-gray-400 mr-1"></i>
                                    {{ $peserta->employee->department->name ?? '-' }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-id-badge text-gray-400 mr-1"></i>
                                    {{ $peserta->employee->jobLevel->name ?? '-' }}
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                @if($pesertaBelumEvaluasi->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-check-circle text-green-500 text-5xl mb-4"></i>
                        <p class="text-gray-600 text-lg">Semua peserta dari department Anda sudah dievaluasi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Step 2: Form Penilaian -->
        <template x-for="pesertaId in selectedPeserta" :key="pesertaId">
            <div class="bg-white rounded-lg shadow mb-6" x-cloak>
                <div class="px-6 py-4 border-b bg-gradient-to-r from-purple-50 to-purple-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800" x-text="getPesertaName(pesertaId)"></h3>
                                <p class="text-sm text-gray-600">Evaluasi Penerapan Hasil Training</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Aspek 1: Peningkatan Keterampilan -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            1. Peningkatan keterampilan setelah mengikuti training
                            <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="grid grid-cols-5 gap-2 mb-3">
                            <template x-for="i in [1,2,3,4,5]" :key="i">
                                <label class="flex flex-col items-center cursor-pointer">
                                    <input type="radio" 
                                           :name="'evaluasi[' + pesertaId + '][peningkatan_keterampilan]'" 
                                           :value="i"
                                           class="mb-2">
                                    <span class="text-xs text-center font-medium" x-text="i"></span>
                                    <span class="text-xs text-center text-gray-600" x-text="getScoreLabel(i)"></span>
                                </label>
                            </template>
                        </div>

                        <label class="block text-sm text-gray-700 mb-2">Uraian/Contoh Konkret:</label>
                        <textarea :name="'evaluasi[' + pesertaId + '][uraian_keterampilan]'" 
                                  rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Berikan contoh konkret peningkatan keterampilan..."></textarea>
                    </div>

                    <!-- Aspek 2: Penerapan Pengetahuan -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            2. Penerapan ilmu/pengetahuan dalam pekerjaan sehari-hari
                            <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="grid grid-cols-5 gap-2 mb-3">
                            <template x-for="i in [1,2,3,4,5]" :key="i">
                                <label class="flex flex-col items-center cursor-pointer">
                                    <input type="radio" 
                                           :name="'evaluasi[' + pesertaId + '][penerapan_pengetahuan]'" 
                                           :value="i"
                                           class="mb-2">
                                    <span class="text-xs text-center font-medium" x-text="i"></span>
                                    <span class="text-xs text-center text-gray-600" x-text="getScoreLabel(i)"></span>
                                </label>
                            </template>
                        </div>

                        <label class="block text-sm text-gray-700 mb-2">Uraian/Contoh Konkret:</label>
                        <textarea :name="'evaluasi[' + pesertaId + '][uraian_penerapan]'" 
                                  rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Berikan contoh konkret penerapan ilmu..."></textarea>
                    </div>

                    <!-- Aspek 3: Perubahan Perilaku -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            3. Perubahan perilaku kerja (inisiatif, disiplin, kerja sama, kepatuhan SOP)
                            <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="grid grid-cols-5 gap-2 mb-3">
                            <template x-for="i in [1,2,3,4,5]" :key="i">
                                <label class="flex flex-col items-center cursor-pointer">
                                    <input type="radio" 
                                           :name="'evaluasi[' + pesertaId + '][perubahan_perilaku]'" 
                                           :value="i"
                                           class="mb-2">
                                    <span class="text-xs text-center font-medium" x-text="i"></span>
                                    <span class="text-xs text-center text-gray-600" x-text="getScoreLabel(i)"></span>
                                </label>
                            </template>
                        </div>

                        <label class="block text-sm text-gray-700 mb-2">Uraian/Contoh Konkret:</label>
                        <textarea :name="'evaluasi[' + pesertaId + '][uraian_perilaku]'" 
                                  rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Berikan contoh konkret perubahan perilaku..."></textarea>
                    </div>

                    <!-- Aspek 4: Dampak Performa -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-semibold text-gray-900 mb-3">
                            4. Dampak pada performa/hasil kerja (efisiensi, kualitas, produktivitas)
                            <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="grid grid-cols-5 gap-2 mb-3">
                            <template x-for="i in [1,2,3,4,5]" :key="i">
                                <label class="flex flex-col items-center cursor-pointer">
                                    <input type="radio" 
                                           :name="'evaluasi[' + pesertaId + '][dampak_performa]'" 
                                           :value="i"
                                           class="mb-2">
                                    <span class="text-xs text-center font-medium" x-text="i"></span>
                                    <span class="text-xs text-center text-gray-600" x-text="getScoreLabel(i)"></span>
                                </label>
                            </template>
                        </div>

                        <label class="block text-sm text-gray-700 mb-2">Uraian/Contoh Konkret:</label>
                        <textarea :name="'evaluasi[' + pesertaId + '][uraian_performa]'" 
                                  rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Berikan contoh konkret dampak pada performa..."></textarea>
                    </div>

                    <!-- Catatan Atasan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                            Catatan/Saran untuk Pengembangan
                        </label>
                        <textarea :name="'evaluasi[' + pesertaId + '][catatan_atasan]'" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Berikan catatan atau saran untuk pengembangan karyawan..."></textarea>
                    </div>
                </div>
            </div>
        </template>

        <!-- Info Box -->
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6" x-show="selectedPeserta.length > 0" x-cloak>
            <div class="flex">
                <i class="fas fa-info-circle text-purple-600 mr-3 mt-1"></i>
                <div class="text-sm text-purple-800">
                    <p class="font-semibold mb-1">Panduan Penilaian:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Evaluasi dilakukan berdasarkan pengamatan penerapan hasil training</li>
                        <li>Berikan uraian konkret untuk setiap aspek penilaian</li>
                        <li>Total skor: Sangat Baik (17-20), Baik (13-16), Cukup (9-12), Perlu Perbaikan (&lt;9)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('evaluasi-atasan.index') }}" 
               class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700"
                    :disabled="selectedPeserta.length === 0">
                <i class="fas fa-save mr-2"></i>Simpan Evaluasi
            </button>
        </div>
    </form>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Semua Peserta Sudah Dievaluasi</h3>
            <p class="text-gray-600 mb-6">Tidak ada peserta dari department Anda yang perlu dievaluasi</p>
            <a href="{{ route('evaluasi-atasan.index') }}" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function evaluasiForm() {
    return {
        selectedPeserta: [],
        pesertaData: @json($pesertaBelumEvaluasi->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->employee->name,
                'department' => $p->employee->department->name ?? '-'
            ];
        })),
        
        getPesertaName(id) {
            const peserta = this.pesertaData.find(p => p.id == id);
            return peserta ? peserta.name : '';
        },
        
        getScoreLabel(score) {
            const labels = {
                1: 'Sangat Kurang',
                2: 'Kurang',
                3: 'Cukup',
                4: 'Baik',
                5: 'Sangat Baik'
            };
            return labels[score];
        },
        
        validateForm(e) {
            if (this.selectedPeserta.length === 0) {
                e.preventDefault();
                alert('Pilih minimal 1 peserta untuk dievaluasi');
                return false;
            }

            // Validate each selected peserta has all required scores
            for (const pesertaId of this.selectedPeserta) {
                const aspects = [
                    'peningkatan_keterampilan',
                    'penerapan_pengetahuan',
                    'perubahan_perilaku',
                    'dampak_performa'
                ];
                
                for (const aspect of aspects) {
                    const radios = document.querySelectorAll(`input[name="evaluasi[${pesertaId}][${aspect}]"]`);
                    const checked = Array.from(radios).some(r => r.checked);
                    
                    if (!checked) {
                        e.preventDefault();
                        alert(`Lengkapi semua aspek penilaian untuk ${this.getPesertaName(pesertaId)}`);
                        return false;
                    }
                }
            }
            
            return true;
        }
    }
}
</script>
@endpush
@endsection
