@extends('layouts.app')

@section('page-title', 'Detail Training')

@section('content')
<div class="max-w-full">
    <!-- Info Training -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Informasi Training</h2>
            <a href="{{ route('training.edit', $training) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-edit mr-2"></i>Edit Training
            </a>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">ID Training</p>
                    <p class="font-medium">TRN-{{ str_pad($training->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Training</p>
                    <p class="font-medium">{{ $training->tanggal_training->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Materi Training</p>
                    <p class="font-medium">{{ $training->materiTraining->nama_materi }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Trainer</p>
                    <p class="font-medium">{{ $training->trainer->nama_trainer }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jumlah Soal</p>
                    <p class="font-medium">{{ $training->jumlah_soal }} Soal</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jumlah Peserta</p>
                    <p class="font-medium">{{ $training->peserta->count() }} Orang</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Peserta -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Peserta & Hasil Training</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peserta</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Skor Pretest</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Skor Posttest</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">N-Gain</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">% Kenaikan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($training->peserta as $index => $peserta)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $peserta->employee->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $peserta->employee->jobLevel->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $peserta->employee->department->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                {{ number_format($peserta->skor_pretest, 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">
                                {{ number_format($peserta->skor_posttest, 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="font-medium">{{ number_format($peserta->n_gain, 2) }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            @if($peserta->kategori_n_gain == 'Tinggi')
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Tinggi</span>
                            @elseif($peserta->kategori_n_gain == 'Sedang')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Sedang</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Rendah</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="font-medium {{ $peserta->persentase_kenaikan > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($peserta->persentase_kenaikan, 2) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('training.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Training
        </a>
    </div>
</div>
@endsection