@extends('layouts.app')

@section('page-title', 'Tambah Materi Training')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Form Tambah Materi Training Baru</h2>
        </div>

        <form action="{{ route('data-master.materi-trainings.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Materi <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_materi" value="{{ old('kode_materi') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kode_materi') border-red-500 @enderror" placeholder="contoh: MT001" required>
                    @error('kode_materi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Materi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_materi" value="{{ old('nama_materi') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_materi') border-red-500 @enderror" placeholder="Nama materi training" required>
                    @error('nama_materi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Materi <span class="text-red-500">*</span></label>
                <select name="jenis_materi" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jenis_materi') border-red-500 @enderror" required>
                    <option value="">-- Pilih Jenis Materi --</option>
                    <option value="Technical" {{ old('jenis_materi') == 'Technical' ? 'selected' : '' }}>Technical</option>
                    <option value="Soft Skill" {{ old('jenis_materi') == 'Soft Skill' ? 'selected' : '' }}>Soft Skill</option>
                    <option value="Leadership" {{ old('jenis_materi') == 'Leadership' ? 'selected' : '' }}>Leadership</option>
                    <option value="Safety" {{ old('jenis_materi') == 'Safety' ? 'selected' : '' }}>Safety</option>
                    <option value="Quality Control" {{ old('jenis_materi') == 'Quality Control' ? 'selected' : '' }}>Quality Control</option>
                    <option value="Lainnya" {{ old('jenis_materi') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('jenis_materi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('data-master.materi-trainings.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection