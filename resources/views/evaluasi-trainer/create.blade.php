@extends('layouts.app')

@section('page-title', 'Tambah Evaluasi Trainer')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    
    <!-- Training Info Card -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">{{ $training->materiTraining->nama_materi ?? 'Training' }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-blue-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-blue-200">Tanggal</p>
                            <p class="font-semibold">{{ $training->tanggal_training->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-chalkboard-teacher text-blue-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-blue-200">Trainer</p>
                            <p class="font-semibold">{{ $training->trainer->nama_trainer ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-users text-blue-200 mr-2"></i>
                        <div>
                            <p class="text-xs text-blue-200">Total Peserta</p>
                            <p class="font-semibold">{{ $training->peserta->count() }} Orang</p>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('evaluasi-trainer.index') }}" 
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
    <form action="{{ route('evaluasi-trainer.store', $training) }}" method="POST" x-data="evaluasiForm()">
        @csrf

        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Form Penilaian Trainer</h3>
                <p class="text-sm text-gray-600 mt-1">Pilih peserta dan berikan penilaian untuk trainer</p>
            </div>

            <div class="p-6 space-y-6">
                
                <!-- Pilih Peserta -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-users mr-1"></i>Pilih Peserta yang Akan Dievaluasi
                        <span class="text-red-500">*</span>
                    </label>
                    
                    @error('peserta_ids')
                        <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="border rounded-lg divide-y max-h-96 overflow-y-auto">
                        <!-- Select All -->
                        <div class="p-4 bg-gray-50">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       x-model="selectAll"
                                       @change="toggleSelectAll()"
                                       class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                <span class="ml-3 text-sm font-medium text-gray-700">
                                    Pilih Semua Peserta (<span x-text="availablePeserta.length"></span>)
                                </span>
                            </label>
                        </div>

                        <!-- Peserta List -->
                        @foreach($pesertaBelumEvaluasi as $peserta)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="peserta_ids[]" 
                                           value="{{ $peserta->id }}"
                                           x-model="selectedPeserta"
                                           class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $peserta->employee->name ?? '-' }}
                                                </p>
                                                <div class="flex items-center space-x-3 mt-1">
                                                    <span class="text-xs text-gray-500">
                                                        <i class="fas fa-building text-gray-400 mr-1"></i>
                                                        {{ $peserta->employee->department->name ?? '-' }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        <i class="fas fa-briefcase text-gray-400 mr-1"></i>
                                                        {{ $peserta->employee->jobLevel->name ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-xs text-gray-500">Skor</div>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                                        Pre: {{ $peserta->skor_pretest }}
                                                    </span>
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                                        Post: {{ $peserta->skor_posttest }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span x-text="selectedPeserta.length"></span> peserta dipilih
                    </p>
                </div>

                <!-- Aspek Penilaian -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-star mr-2 text-yellow-500"></i>
                        Penilaian Aspek Training
                    </h4>

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
                <a href="{{ route('evaluasi-trainer.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-700 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition
                               disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="selectedPeserta.length === 0">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Evaluasi (<span x-text="selectedPeserta.length"></span> Peserta)
                </button>
            </div>
        </div>
    </form>
    @else
    <!-- All Done Message -->
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-green-600 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Evaluasi Lengkap!</h3>
            <p class="text-gray-600 mb-6">
                Semua peserta training ini sudah dievaluasi.
            </p>
            <div class="flex justify-center space-x-3">
                <a href="{{ route('evaluasi-trainer.show', $training) }}" 
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-chart-bar mr-2"></i>Lihat Hasil
                </a>
                <a href="{{ route('evaluasi-trainer.index') }}" 
                   class="px-4 py-2 border border-gray-300 hover:bg-gray-50 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Peserta yang Sudah Dievaluasi -->
    @if($pesertaSudahEvaluasi->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                Peserta yang Sudah Dievaluasi ({{ $pesertaSudahEvaluasi->count() }})
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($pesertaSudahEvaluasi as $peserta)
                    <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                        <i class="fas fa-user-check text-green-600 text-xl mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $peserta->employee->name ?? '-' }}</p>
                            <p class="text-xs text-gray-600">{{ $peserta->employee->department->name ?? '-' }}</p>
                        </div>
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
function evaluasiForm() {
    return {
        selectedPeserta: [],
        selectAll: false,
        availablePeserta: @json($pesertaBelumEvaluasi->pluck('id')),
        
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedPeserta = [...this.availablePeserta];
            } else {
                this.selectedPeserta = [];
            }
        },
        
        init() {
            this.$watch('selectedPeserta', value => {
                this.selectAll = value.length === this.availablePeserta.length;
            });
        }
    }
}
</script>
@endpush
@endsection