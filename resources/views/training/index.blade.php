@extends('layouts.app')

@section('page-title', 'Data Training')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Daftar Training</h2>
        <a href="{{ route('training.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition">
            <i class="fas fa-plus mr-2"></i>Tambah Training
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Training</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trainer</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah Peserta</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($trainings as $index => $training)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ ($trainings->currentPage() - 1) * $trainings->perPage() + $index + 1 }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        TRN-{{ str_pad($training->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $training->tanggal_training->format('d M Y') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $training->tanggal_training->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $training->materiTraining->nama_materi }}
                        </div>
                        @if($training->jenis_training)
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                {{ $training->jenis_training }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $training->trainer->nama_trainer }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $training->peserta->count() }} Peserta
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('training.show', $training) }}" 
                               class="text-blue-600 hover:text-blue-800 transition" 
                               title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('training.edit', $training) }}" 
                               class="text-green-600 hover:text-green-800 transition" 
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('training.destroy', $training) }}" 
                                  method="POST" 
                                  class="inline" 
                                  onsubmit="return confirm('Yakin hapus training ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 transition" 
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-medium">Belum ada data training</p>
                            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Training" untuk menambah data baru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($trainings->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $trainings->links() }}
        </div>
    @endif
</div>
@endsection