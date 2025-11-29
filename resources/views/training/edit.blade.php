@extends('layouts.app')

@section('page-title', 'Edit Training')

@section('content')
<div class="max-w-5xl">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Form Edit Training</h2>
        </div>

        <form action="{{ route('training.update', $training) }}" method="POST" class="p-6" x-data="trainingForm()">
            @csrf
            @method('PUT')

            <!-- Info Training -->
            <div class="mb-6">
                <h3 class="text-md font-semibold text-gray-700 mb-4">Informasi Training</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trainer <span class="text-red-500">*</span></label>
                        <select name="trainer_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Pilih Trainer --</option>
                            @foreach($trainers as $trainer)
                                <option value="{{ $trainer->id }}" {{ old('trainer_id', $training->trainer_id) == $trainer->id ? 'selected' : '' }}>
                                    {{ $trainer->nama_trainer }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Materi Training <span class="text-red-500">*</span></label>
                        <select name="materi_training_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Pilih Materi --</option>
                            @foreach($materiTrainings as $materi)
                                <option value="{{ $materi->id }}" {{ old('materi_training_id', $training->materi_training_id) == $materi->id ? 'selected' : '' }}>
                                    {{ $materi->nama_materi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Training <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_training" value="{{ old('tanggal_training', $training->tanggal_training->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Soal <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_soal" value="{{ old('jumlah_soal', $training->jumlah_soal) }}" min="1" x-model="jumlahSoal" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
            </div>

            <!-- Peserta Training -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-md font-semibold text-gray-700">Daftar Peserta Training</h3>
                    <button type="button" @click="addPeserta()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Peserta
                    </button>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Karyawan</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pretest Benar</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Posttest Benar</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="(peserta, index) in pesertaList" :key="index">
                                <tr>
                                    <td class="px-4 py-2">
                                        <select :name="'peserta['+index+'][employee_id]'" 
                                                x-model="peserta.employee_id"
                                                class="w-full border rounded px-2 py-1 text-sm" 
                                                @change="updatePesertaInfo(index, $event)" 
                                                required>
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}" 
                                                    data-jabatan="{{ $emp->jobLevel->name ?? '-' }}" 
                                                    data-department="{{ $emp->department->name ?? '-' }}">
                                                    {{ $emp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-2 text-sm" x-text="peserta.jabatan"></td>
                                    <td class="px-4 py-2 text-sm" x-text="peserta.department"></td>
                                    <td class="px-4 py-2">
                                        <input type="number" 
                                               :name="'peserta['+index+'][pretest_benar]'" 
                                               x-model="peserta.pretest_benar"
                                               min="0" 
                                               class="w-20 border rounded px-2 py-1 text-sm" 
                                               required>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" 
                                               :name="'peserta['+index+'][posttest_benar]'" 
                                               x-model="peserta.posttest_benar"
                                               min="0" 
                                               class="w-20 border rounded px-2 py-1 text-sm" 
                                               required>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <button type="button" @click="removePeserta(index)" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('training.show', $training) }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function trainingForm() {
    const pesertaData = @json($training->peserta);
    const employeesData = @json($employees);
    
    const initialPeserta = pesertaData.map(p => {
        const emp = employeesData.find(e => e.id === p.employee_id);
        return {
            employee_id: p.employee_id,
            jabatan: emp?.job_level?.name || '-',
            department: emp?.department?.name || '-',
            pretest_benar: p.pretest_benar,
            posttest_benar: p.posttest_benar
        };
    });
    
    return {
        jumlahSoal: {{ $training->jumlah_soal }},
        pesertaList: initialPeserta,
        
        addPeserta() {
            this.pesertaList.push({
                employee_id: '',
                jabatan: '-',
                department: '-',
                pretest_benar: 0,
                posttest_benar: 0
            });
        },
        
        removePeserta(index) {
            if (confirm('Yakin ingin menghapus peserta ini?')) {
                this.pesertaList.splice(index, 1);
            }
        },
        
        updatePesertaInfo(index, event) {
            const option = event.target.selectedOptions[0];
            this.pesertaList[index].jabatan = option.dataset.jabatan || '-';
            this.pesertaList[index].department = option.dataset.department || '-';
        }
    }
}
</script>
@endpush
@endsection
