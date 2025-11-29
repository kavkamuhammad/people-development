@extends('layouts.app')

@section('page-title', 'Level Jabatan')

@section('content')
<div x-data="jobLevelManager()" class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Level Jabatan</h2>
        <button @click="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>Tambah Level
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Urutan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah Karyawan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($jobLevels as $level)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-center">
                        <span class="px-3 py-1 bg-gray-200 text-gray-800 rounded-full font-medium">{{ $level->level_order }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $level->code }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $level->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $level->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-center">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $level->employees->count() }} orang</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($level->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        <button @click="editLevel({{ $level->id }}, '{{ $level->code }}', '{{ $level->name }}', {{ $level->level_order }}, '{{ $level->description }}', {{ $level->is_active ? 'true' : 'false' }})" class="text-blue-600 hover:text-blue-800 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button @click="deleteLevel({{ $level->id }}, '{{ $level->name }}')" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data level jabatan</td>
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
                <h3 class="text-lg font-semibold mb-4" x-text="isEdit ? 'Edit Level Jabatan' : 'Tambah Level Jabatan'"></h3>
                <form @submit.prevent="submitForm()">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.code" class="w-full border rounded-lg px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutan <span class="text-red-500">*</span></label>
                            <input type="number" x-model="form.level_order" min="1" class="w-full border rounded-lg px-3 py-2" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama <span class="text-red-500">*</span></label>
                        <input type="text" x-model="form.name" class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea x-model="form.description" rows="2" class="w-full border rounded-lg px-3 py-2"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" x-model="form.is_active" class="rounded border-gray-300">
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
function jobLevelManager() {
    return {
        showModal: false,
        isEdit: false,
        editId: null,
        form: { code: '', name: '', level_order: 1, description: '', is_active: true },
        openModal() { this.isEdit = false; this.editId = null; this.form = { code: '', name: '', level_order: 1, description: '', is_active: true }; this.showModal = true; },
        closeModal() { this.showModal = false; },
        editLevel(id, code, name, order, desc, active) { this.isEdit = true; this.editId = id; this.form = { code, name, level_order: order, description: desc, is_active: active }; this.showModal = true; },
        async submitForm() {
            const url = this.isEdit ? `/data-master/job-levels/${this.editId}` : '/data-master/job-levels';
            const method = this.isEdit ? 'PATCH' : 'POST';
            try {
                const res = await fetch(url, {
                    method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(this.form)
                });
                const data = await res.json();
                if (data.success) { location.reload(); } else { alert(data.message); }
            } catch (e) { alert('Terjadi kesalahan'); }
        },
        async deleteLevel(id, name) {
            if (!confirm(`Yakin hapus level "${name}"?`)) return;
            try {
                const res = await fetch(`/data-master/job-levels/${id}`, {
                    method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) { location.reload(); } else { alert(data.message); }
            } catch (e) { alert('Gagal menghapus level'); }
        }
    }
}
</script>
@endpush
@endsection