@extends('layouts.app')

@section('page-title', 'Observasi Training')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Observasi Training</h2>
                <p class="text-gray-600 text-sm mt-1">Observasi proses pelaksanaan training</p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Training</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $trainings->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Sudah Diobservasi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $trainings->where('observasi_training_exists', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-full">
                    <i class="fas fa-hourglass-half text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Belum Diobservasi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $trainings->where('observasi_training_exists', false)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Training List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Training yang Sudah Selesai</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trainer</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($trainings as $index => $training)
                        @php
                            $hasObservasi = $training->observasi_training_exists;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ ($trainings->currentPage() - 1) * $trainings->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $training->tanggal_training->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $training->tanggal_training->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $training->materiTraining->nama_materi ?? '-' }}
                                </div>
                                @if($training->jenis_training)
                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                        {{ $training->jenis_training }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $training->trainer->nama_trainer ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($hasObservasi)
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Sudah
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-orange-100 text-orange-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Belum
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    @if($hasObservasi)
                                        <a href="{{ route('observasi-training.show', $training->id) }}" 
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Lihat Hasil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('observasi-training.edit', $training->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('observasi-training.create', $training->id) }}" 
                                           class="text-green-600 hover:text-green-900"
                                           title="Observasi">
                                            <i class="fas fa-plus-circle"></i>
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('training.show', $training->id) }}" 
                                       class="text-gray-600 hover:text-gray-900"
                                       title="Detail Training">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Belum ada training yang selesai</p>
                                    <p class="text-gray-400 text-sm mt-1">Training yang sudah selesai akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($trainings->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $trainings->links() }}
            </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Catatan:</strong> Observasi training dilakukan untuk menilai proses pelaksanaan training. 
                    Klik tombol <i class="fas fa-plus-circle"></i> untuk melakukan observasi, 
                    atau <i class="fas fa-eye"></i> untuk melihat hasil observasi.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
