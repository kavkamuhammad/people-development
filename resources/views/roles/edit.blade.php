@extends('layouts.app')

@section('page-title', 'Edit Role')

@section('content')
<div class="w-full">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Form Edit Role</h2>
        </div>

        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="p-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Role <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                        value="{{ old('name', $role->name) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               @error('name') border-red-500 @enderror"
                        placeholder="contoh: manager" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Display Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="display_name"
                        value="{{ old('display_name', $role->display_name) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               @error('display_name') border-red-500 @enderror"
                        placeholder="contoh: Manager" required>
                    @error('display_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Deskripsi role...">{{ old('description', $role->description) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto bg-gray-50">

                    <div class="mb-3 pb-3 border-b">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="select-all"
                                class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Pilih Semua</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($permissions as $permission)
                            <label class="inline-flex items-center cursor-pointer hover:bg-white p-2 rounded">
                                <input type="checkbox" name="permissions[]"
                                    value="{{ $permission->id }}"
                                    class="permission-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ in_array($permission->id, old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ $permission->display_name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('roles.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.permission-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const all = document.querySelectorAll('.permission-checkbox');
            const checked = document.querySelectorAll('.permission-checkbox:checked');
            selectAll.checked = all.length === checked.length;
        });
    });

    // Initial state
    const all = document.querySelectorAll('.permission-checkbox');
    const checked = document.querySelectorAll('.permission-checkbox:checked');
    selectAll.checked = all.length > 0 && all.length === checked.length;
});
</script>
@endpush
