@extends('layouts.app')

@section('page-title', 'Training Record Karyawan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Training Record Karyawan</h1>
            <p class="text-gray-600 mt-2">Riwayat training lengkap karyawan</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('training-record.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nama</label>
                <input type="text" 
                       name="nama" 
                       value="{{ request('nama') }}"
                       placeholder="Ketik nama karyawan..."
                       class="w-full border-gray-300 rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                <select name="departement" class="w-full border-gray-300 rounded-lg">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('departement') == $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
                @if(request()->hasAny(['nama', 'departement']))
                    <a href="{{ route('training-record.index') }}" 
                       class="ml-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 whitespace-nowrap">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Employees List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Departemen</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Training</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $employee->nik }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $employee->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $employee->jabatan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $employee->departement }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $employee->trainingRecords->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('training-record.show', $employee) }}" 
                                       class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                        <i class="fas fa-eye mr-1"></i>
                                        Lihat Detail
                                    </a>
                                    @if($employee->trainingRecords->count() > 0)
                                        <a href="{{ route('training-record.export', $employee) }}" 
                                           class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                            <i class="fas fa-download mr-1"></i>
                                            Export
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users-slash text-gray-400 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">Tidak ada karyawan ditemukan</p>
                                    @if(request()->hasAny(['nama', 'departement']))
                                        <p class="text-gray-400 text-sm mt-2">Coba ubah filter pencarian</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $employees->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Statistics Summary -->
    @if($employees->isNotEmpty())
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Karyawan</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $employees->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Training</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $employees->sum(function($e) { return $e->trainingRecords->count(); }) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-building text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Departemen</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $departments->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Avg Training/Karyawan</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $employees->count() > 0 ? number_format($employees->sum(function($e) { return $e->trainingRecords->count(); }) / $employees->count(), 1) : 0 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection