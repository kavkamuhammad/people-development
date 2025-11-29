@extends('layouts.app')

@section('page-title', 'Hasil Observasi Training')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Hasil Observasi Training</h1>
            <p class="text-gray-600 mt-2">{{ $observasi->training->materiTraining->nama_materi ?? '-' }}</p>
        </div>
        <a href="{{ route('observasi-training.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Training Info Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Informasi Training</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-600">Materi</p>
                <p class="font-semibold">{{ $observasi->materi_training }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal</p>
                <p class="font-semibold">{{ $observasi->tanggal_training->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Trainer</p>
                <p class="font-semibold">{{ $observasi->trainer->nama_trainer ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Observer</p>
                <p class="font-semibold">{{ $observasi->user->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">Total Ada</p>
                    <p class="text-4xl font-bold">{{ $observasi->total_ada }}</p>
                    <p class="text-green-100 text-sm mt-1">dari {{ count($aspekPenilaian) }} aspek</p>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-full">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm mb-1">Total Tidak</p>
                    <p class="text-4xl font-bold">{{ $observasi->total_tidak }}</p>
                    <p class="text-red-100 text-sm mt-1">dari {{ count($aspekPenilaian) }} aspek</p>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-full">
                    <i class="fas fa-times-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm mb-1">Persentase</p>
                    <p class="text-4xl font-bold">{{ $observasi->percentage_ada }}%</p>
                    <p class="text-purple-100 text-sm mt-1">Aspek Terpenuhi</p>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-full">
                    <i class="fas fa-chart-pie text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress Aspek Terpenuhi</h3>
        <div class="relative">
            <div class="flex mb-2 items-center justify-between">
                <div>
                    <span class="text-xs font-semibold inline-block text-green-600">
                        {{ $observasi->total_ada }} Ada
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-xs font-semibold inline-block text-red-600">
                        {{ $observasi->total_tidak }} Tidak
                    </span>
                </div>
            </div>
            <div class="overflow-hidden h-4 mb-4 text-xs flex rounded-full bg-gray-200">
                <div style="width:{{ $observasi->percentage_ada }}%" 
                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-green-500 to-green-600 transition-all duration-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Aspek Penilaian -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Detail Aspek Penilaian</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($aspekPenilaian as $field => $label)
                    @php
                        $nilai = $observasi->$field;
                        $isAda = $nilai === 'Ada';
                    @endphp
                    <div class="border rounded-lg p-4 {{ $isAda ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-700 mb-1">
                                    {{ $loop->iteration }}. {{ $label }}
                                </p>
                            </div>
                            <div class="ml-3">
                                @if($isAda)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-600 text-white">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Ada
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-600 text-white">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Tidak
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Catatan -->
    @if($observasi->catatan)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>Catatan Observasi
        </h3>
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <p class="text-gray-700 whitespace-pre-line">{{ $observasi->catatan }}</p>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                Observasi dibuat pada {{ $observasi->created_at->format('d F Y, H:i') }}
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('observasi-training.edit', $observasi->training_id) }}" 
                   class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>Edit Observasi
                </a>
                <form action="{{ route('observasi-training.destroy', $observasi->training_id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Yakin ingin menghapus observasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
