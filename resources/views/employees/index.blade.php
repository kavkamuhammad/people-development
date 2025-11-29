@extends('layouts.app')

@section('page-title', 'Karyawan')

@section('content')
<div x-data="employeeManager()" class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Karyawan</h2>
        <button @click="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i>Tambah Karyawan
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Job Level</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($employees as $emp)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $emp->employee_id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $emp->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $emp->email }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">{{ $emp->department->name ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-800">{{ $emp->jobLevel->name ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($emp->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        <button @click="editEmp({{ json_encode($emp) }})" class="text-blue-600 hover:text-blue-800 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button @click="deleteEmp({{ $emp->id }}, '{{ $emp->name }}')" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data karyawan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="closeModal()"></div>
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
                <h3 class="text-lg font-semibold mb-4" x-text="isEdit ? 'Edit Karyawan' : 'Tambah Karyawan'"></h3>
                <form @submit.prevent="submitForm()">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employee ID <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.employee_id" class="w-full border rounded-lg px-3 py-2" :readonly="isEdit" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama <span class="text-red-500">*</span></label>
                            <input type="text" x-model="form.name" class="w-full border rounded-lg px-3 py-2" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" x-model="form.email" class="w-full border rounded-lg px-3 py-2" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                            <select x-model="form.department_id" class="w-full border rounded-lg px-3 py-2" required>
                                <option value="">-- Pilih --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Job Level <span class="text-red-500">*</span></label>
                            <select x-model="form.job_level_id" class="w-full border rounded-lg px-3 py-2" required>
                                <option value="">-- Pilih --</option>
                                @foreach($jobLevels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }} ({{ $level->code }})</option>
                                @endforeach
                            </select>
                        </div>
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
function employeeManager() {
    return {
        showModal: false,
        isEdit: false,
        editId: null,
        form: { employee_id: '', name: '', email: '', department_id: '', job_level_id: '', is_active: true },
        openModal() { this.isEdit = false; this.editId = null; this.form = { employee_id: '', name: '', email: '', department_id: '', job_level_id: '', is_active: true }; this.showModal = true; },
        closeModal() { this.showModal = false; },
        editEmp(emp) { this.isEdit = true; this.editId = emp.id; this.form = { employee_id: emp.employee_id, name: emp.name, email: emp.email, department_id: emp.department_id, job_level_id: emp.job_level_id, is_active: emp.is_active }; this.showModal = true; },
        async submitForm() {
            const url = this.isEdit ? `/data-master/employees/${this.editId}` : '/data-master/employees';
            const method = this.isEdit ? 'PATCH' : 'POST';
            try {
                const res = await fetch(url, {
                    method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(this.form)
                });
                const data = await res.json();
                if (data.success) { location.reload(); } else { alert(data.message || 'Validasi gagal'); }
            } catch (e) { alert('Terjadi kesalahan'); }
        },
        async deleteEmp(id, name) {
            if (!confirm(`Yakin hapus karyawan "${name}"?`)) return;
            try {
                const res = await fetch(`/data-master/employees/${id}`, {
                    method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) { location.reload(); } else { alert(data.message); }
            } catch (e) { alert('Gagal menghapus karyawan'); }
        }
    }
}
</script>
@endpush
@endsection