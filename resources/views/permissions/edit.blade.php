@extends('layouts.app')

@section('page-title', 'Edit Permission')

@section('content')
<div class="w-full max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Form Edit Permission</h2>
        </div>

        <form action="{{ route('permissions.update', $permission->id) }}" method="POST" class="p-6">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name"
                    value="{{ old('name', $permission->name) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           @error('name') border-red-500 @enderror"
                    placeholder="contoh: view-reports"
                    required>
                <p class="text-xs text-gray-500 mt-1">Gunakan format: action-resource (lowercase, gunakan dash)</p>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Display Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="display_name"
                    value="{{ old('display_name', $permission->display_name) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           @error('display_name') border-red-500 @enderror"
                    placeholder="contoh: View Reports"
                    required>
                <p class="text-xs text-gray-500 mt-1">Nama yang ditampilkan di form role</p>
                @error('display_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Deskripsi permission...">{{ old('description', $permission->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info jika permission digunakan -->
            @if($permission->roles->count() > 0)
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Permission ini digunakan oleh:</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($permission->roles as $role)
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">
                                    {{ $role->display_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('permissions.index') }}"
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
