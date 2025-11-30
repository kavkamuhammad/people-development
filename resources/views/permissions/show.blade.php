@extends('layouts.app')

@section('page-title', 'Detail Permission')

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Detail Permission</h2>
            <div class="space-x-2">
                <a href="{{ route('permissions.edit', $permission->id) }}"
                   class="px-3 py-1 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                <a href="{{ route('permissions.index') }}"
                   class="px-3 py-1 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Permission Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Name</label>
                    <p class="text-gray-900 font-mono bg-gray-100 px-3 py-2 rounded">{{ $permission->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Display Name</label>
                    <p class="text-gray-900 font-semibold">{{ $permission->display_name }}</p>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                <p class="text-gray-900">{{ $permission->description ?? '-' }}</p>
            </div>

            <!-- Roles Using This Permission -->
            <div class="border-t pt-6">
                <h3 class="text-md font-semibold text-gray-800 mb-4">
                    <i class="fas fa-user-shield mr-2 text-blue-600"></i>
                    Roles yang Menggunakan Permission Ini ({{ $permission->roles->count() }})
                </h3>

                @if($permission->roles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($permission->roles as $role)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $role->display_name }}</h4>
                                        <p class="text-xs text-gray-500 mt-1 font-mono">{{ $role->name }}</p>
                                        @if($role->description)
                                            <p class="text-sm text-gray-600 mt-2">{{ $role->description }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('roles.show', $role->id) }}"
                                       class="text-blue-600 hover:text-blue-800 text-sm"
                                       title="Lihat detail role">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <i class="fas fa-info-circle text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-600">Permission ini belum digunakan oleh role manapun</p>
                    </div>
                @endif
            </div>

            <!-- Timestamps -->
            <div class="border-t mt-6 pt-6">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-medium">Created at:</span>
                        {{ $permission->created_at->format('d M Y, H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">Updated at:</span>
                        {{ $permission->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <!-- Delete Button -->
            <div class="border-t mt-6 pt-6">
                <form action="{{ route('permissions.destroy', $permission->id) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus permission ini? Permission yang sedang digunakan tidak dapat dihapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                            {{ $permission->roles->count() > 0 ? 'disabled' : '' }}>
                        <i class="fas fa-trash mr-2"></i>Hapus Permission
                    </button>
                    @if($permission->roles->count() > 0)
                        <p class="text-sm text-red-600 mt-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Permission tidak dapat dihapus karena masih digunakan oleh {{ $permission->roles->count() }} role
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
