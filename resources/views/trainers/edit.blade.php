@extends('layouts.app')

@section('page-title', 'Edit Trainer')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Form Edit Trainer: {{ $trainer->nama_trainer }}</h2>
        </div>

        <form action="{{ route('trainers.update', $trainer) }}" method="POST" class="p-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Trainer <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_trainer" value="{{ old('kode_trainer', $trainer->kode_trainer) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kode_trainer') border-red-500 @enderror" placeholder="contoh: TRN001" required>
                    @error('kode_trainer')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Trainer <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_trainer" value="{{ old('nama_trainer', $trainer->nama_trainer) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_trainer') border-red-500 @enderror" placeholder="Nama lengkap trainer" required>
                    @error('nama_trainer')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="1" {{ old('status', $trainer->status) == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status', $trainer->status) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('data-master.trainers.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection