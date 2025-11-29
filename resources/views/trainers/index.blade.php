@extends('layouts.app')

@section('page-title', 'Data Trainer')

@section('content')
<div x-data="trainerManager()" class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Trainer</h2>
        <button @click="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>Tambah Trainer
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Trainer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Trainer</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($trainers as $trainer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $trainer->kode_trainer }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $trainer->nama_trainer }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($trainer->status)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        <button @click="editTrainer({{ $trainer->id }}, '{{ $trainer->kode_trainer }}', '{{ $trainer->nama_trainer }}', {{ $trainer->status ? 'true' : 'false' }})" class="text-blue-600 hover:text-blue-800 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button @click="deleteTrainer({{ $trainer->id }}, '{{ $trainer->nama_trainer }}')" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data trainer</td>
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
                <h3 class="text-lg font-semibold mb-4" x-text="isEdit ? 'Edit Trainer' : 'Tambah Trainer'"></h3>
                <form @submit.prevent="submitForm()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Trainer <span class="text-red-500">*</span></label>
                        <input type="text" x-model="form.kode_trainer" class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Trainer <span class="text-red-500">*</span></label>
                        <input type="text" x-model="form.nama_trainer" class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" x-model="form.status" class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
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
function trainerManager() {
    return {
        showModal: false,
        isEdit: false,
        editId: null,
        form: { kode_trainer: '', nama_trainer: '', status: true },
        openModal() { 
            this.isEdit = false; 
            this.editId = null; 
            this.form = { kode_trainer: '', nama_trainer: '', status: true }; 
            this.showModal = true; 
        },
        closeModal() { 
            this.showModal = false; 
        },
        editTrainer(id, kode, nama, status) { 
            this.isEdit = true; 
            this.editId = id; 
            this.form = { kode_trainer: kode, nama_trainer: nama, status: status }; 
            this.showModal = true; 
        },
        async submitForm() {
            const url = this.isEdit ? `/trainers/${this.editId}` : '/trainers';
            
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            if (this.isEdit) formData.append('_method', 'PATCH');
            formData.append('kode_trainer', this.form.kode_trainer);
            formData.append('nama_trainer', this.form.nama_trainer);
            formData.append('status', this.form.status ? '1' : '0');
            
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();

                if (!res.ok) {
                    // Handle validation errors
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('\n');
                        alert('Kesalahan validasi:\n' + errorMessages);
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                    }
                    return;
                }

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
        async deleteTrainer(id, name) {
            if (!confirm(`Yakin hapus trainer "${name}"?`)) return;
            try {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'DELETE');

                const res = await fetch(`/trainers/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();

                if (!res.ok) {
                    alert(data.message || 'Gagal menghapus');
                    return;
                }

                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Gagal menghapus');
                }
            } catch (e) {
                console.error(e);
                alert('Gagal menghapus trainer: ' + e.message);
            }
        }
    }
}
</script>
@endpush
@endsection