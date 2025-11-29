@extends('layouts.app')

@section('page-title', 'Tambah Role')

@section('content')
<div class="w-full">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Form Tambah Role Baru</h2>
        </div>

        <form action="{{ route('roles.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Role <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" placeholder="contoh: manager" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Name <span class="text-red-500">*</span></label>
                    <input type="text" name="display_name" value="{{ old('display_name') }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 @error('display_name') border-red-500 @enderror" placeholder="contoh: Manager" required>
                    @error('display_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>
                <div class="border rounded-lg p-4 max-h-64 overflow-y-auto">
                    <div class="mb-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Pilih Semua</span>
                        </label>
                    </div>
                    <hr class="my-2">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($permissions as $permission)
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                class="permission-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">{{ $permission->display_name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('roles.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('select-all').addEventListener('change', function() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = this.checked);
});
</script>
@endpush
@endsection