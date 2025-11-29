@extends('layouts.app')

@section('page-title', 'Data Materi Training')

@section('content')
<div x-data="materiTrainingManager()" class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Materi Training</h2>
        <button @click="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>Tambah Materi
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Materi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Materi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Materi</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($materiTrainings as $materi)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $materi->kode_materi }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $materi->nama_materi }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $materi->jenis_materi }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        <button @click="editMateri({{ $materi->id }}, '{{ $materi->kode_materi }}', '{{ $materi->nama_materi }}', '{{ $materi->jenis_materi }}')" class="text-blue-600 hover:text-blue-800 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button @click="deleteMateri({{ $materi->id }}, '{{ $materi->nama_materi }}')" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data materi training</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal()"></div>
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold mb-4" x-text="isEdit ? 'Edit Materi Training' : 'Tambah Materi Training'"></h3>
                <form @submit.prevent="submitForm()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Materi <span class="text-red-500">*</span></label>
                        <input type="text" x-model="form.kode_materi" class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Materi <span class="text-red-500">*</span></label>
                        <input type="text" x-model="form.nama_materi" class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Materi <span class="text-red-500">*</span></label>
                        <select x-model="form.jenis_materi" class="w-full border rounded-lg px-3 py-2" required>
                            <option value="">-- Pilih Jenis Materi --</option>
                            <option value="Technical">Technical</option>
                            <option value="Soft Skills">Soft Skills</option>
                            <option value="Leadership">Leadership</option>
                            <option value="Management">Management</option>
                            <option value="Product Knowledge">Product Knowledge</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function materiTrainingManager() {
    return {
        showModal: false,
        isEdit: false,
        editId: null,
        form: { kode_materi: '', nama_materi: '', jenis_materi: '' },
        openModal() { 
            this.isEdit = false; 
            this.editId = null; 
            this.form = { kode_materi: '', nama_materi: '', jenis_materi: '' }; 
            this.showModal = true; 
        },
        closeModal() { 
            this.showModal = false; 
        },
        editMateri(id, kode, nama, jenis) { 
            this.isEdit = true; 
            this.editId = id; 
            this.form = { kode_materi: kode, nama_materi: nama, jenis_materi: jenis }; 
            this.showModal = true; 
        },
        async submitForm() {
            const url = this.isEdit ? `/materi-trainings/${this.editId}` : '/materi-trainings';
            
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            if (this.isEdit) formData.append('_method', 'PATCH');
            formData.append('kode_materi', this.form.kode_materi);
            formData.append('nama_materi', this.form.nama_materi);
            formData.append('jenis_materi', this.form.jenis_materi);
            
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                        'Accept': 'application/json' 
                    },
                    body: formData
                });
                
                if (!res.ok) {
                    const errorData = await res.json();
                    alert(errorData.message || 'Terjadi kesalahan');
                    return;
                }
                
                const data = await res.json();
                if (data.success) { 
                    location.reload(); 
                } else { 
                    alert(data.message || 'Terjadi kesalahan'); 
                }
            } catch (e) { 
                console.error(e);
                alert('Terjadi kesalahan: ' + e.message); 
            }
        },
        async deleteMateri(id, name) {
            if (!confirm(`Yakin hapus materi "${name}"?`)) return;
            try {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'DELETE');
                
                const res = await fetch(`/materi-trainings/${id}`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                        'Accept': 'application/json' 
                    },
                    body: formData
                });
                
                if (!res.ok) {
                    const errorData = await res.json();
                    alert(errorData.message || 'Gagal menghapus');
                    return;
                }
                
                const data = await res.json();
                if (data.success) { 
                    location.reload(); 
                } else { 
                    alert(data.message || 'Gagal menghapus'); 
                }
            } catch (e) { 
                console.error(e);
                alert('Gagal menghapus materi: ' + e.message); 
            }
        }
    }
}
</script>
@endpush
@endsection