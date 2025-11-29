@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Alert Evaluasi Trainer Belum Lengkap -->
    @if(isset($trainingPerluEvaluasi) && $trainingPerluEvaluasi > 0)
        <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-lg shadow-sm animate-pulse">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-orange-400 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-orange-800">
                            Perhatian! Ada {{ $trainingPerluEvaluasi }} training yang belum lengkap evaluasinya
                        </h3>
                        <p class="text-sm text-orange-700 mt-1">
                            Segera lengkapi evaluasi trainer untuk training yang sudah selesai
                        </p>
                    </div>
                </div>
                <a href="{{ route('evaluasi-trainer.index') }}" 
                class="ml-4 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-medium transition whitespace-nowrap">
                    <i class="fas fa-arrow-right mr-2"></i>Evaluasi Sekarang
                </a>
            </div>
        </div>
    @endif
    <!-- END Alert -->

    <!-- Stats Cards Row 1 - Original Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <!-- Total Employees -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-id-card text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Employees</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalEmployees }}</p>
                </div>
            </div>
        </div>

        <!-- Total Departments -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-building text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Departments</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalDepartments }}</p>
                </div>
            </div>
        </div>

        <!-- Total Roles -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-user-shield text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Roles</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalRoles }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 2 - Training Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Trainings -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Training</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalTrainings }}</p>
                </div>
                <i class="fas fa-chalkboard-teacher text-4xl opacity-30"></i>
            </div>
        </div>

        <!-- Total Peserta -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Peserta</p>
                    <p class="text-3xl font-bold mt-1">{{ $totalPeserta }}</p>
                </div>
                <i class="fas fa-user-graduate text-4xl opacity-30"></i>
            </div>
        </div>

        <!-- Upcoming Trainings -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Training Mendatang</p>
                    <p class="text-3xl font-bold mt-1">{{ $upcomingTrainings }}</p>
                </div>
                <i class="fas fa-calendar-alt text-4xl opacity-30"></i>
            </div>
        </div>

        <!-- Completed Trainings -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Training Selesai</p>
                    <p class="text-3xl font-bold mt-1">{{ $completedTrainings }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-30"></i>
            </div>
        </div>
    </div>

    <!-- Average Scores & Kategori Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Average Scores Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rata-rata Skor Training</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">Pretest</span>
                        <span class="text-lg font-bold text-blue-600">{{ number_format($averagePretest, 1) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ ($averagePretest / 100) * 100 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">Posttest</span>
                        <span class="text-lg font-bold text-green-600">{{ number_format($averagePosttest, 1) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: {{ ($averagePosttest / 100) * 100 }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">N-Gain</span>
                        <span class="text-lg font-bold text-purple-600">{{ number_format($averageNGain, 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-purple-600 h-3 rounded-full" style="width: {{ ($averageNGain / 1) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Kategori Peserta</h3>
            <div class="space-y-3">
                @php
                    $total = array_sum($kategoriDistribution);
                @endphp
                
                @foreach(['Tinggi' => 'green', 'Sedang' => 'yellow', 'Rendah' => 'red'] as $kategori => $color)
                    @php
                        $count = $kategoriDistribution[$kategori] ?? 0;
                        $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $kategori }}</span>
                            <span class="text-sm font-bold text-{{ $color }}-600">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-6">
                            <div class="bg-{{ $color }}-500 h-6 rounded-full flex items-center justify-center text-white text-xs font-semibold" 
                                 style="width: {{ $percentage }}%">
                                @if($percentage > 10)
                                    {{ number_format($percentage, 0) }}%
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Trainer Performance -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Performa Trainer</h3>
            <span class="text-sm text-gray-500">Top 10 Trainer</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trainer</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Evaluasi</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Rating Rata-rata</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($trainerPerformance as $index => $trainer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-bold text-gray-900">
                                @if($index == 0)
                                    <i class="fas fa-trophy text-yellow-500"></i>
                                @elseif($index == 1)
                                    <i class="fas fa-medal text-gray-400"></i>
                                @elseif($index == 2)
                                    <i class="fas fa-medal text-orange-400"></i>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $trainer->nama }}</td>
                            <td class="px-4 py-3 text-sm text-center">{{ $trainer->total_evaluasi }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-lg font-bold
                                    @if($trainer->avg_rating >= 3.5) text-green-600
                                    @elseif($trainer->avg_rating >= 2.5) text-yellow-600
                                    @else text-red-600
                                    @endif">
                                    {{ number_format($trainer->avg_rating, 2) }}
                                </span>
                                <span class="text-xs text-gray-500">/ 4.00</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($trainer->avg_rating >= 3.5)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Excellent</span>
                                @elseif($trainer->avg_rating >= 2.5)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Good</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Need Improvement</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data evaluasi trainer
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Evaluasi Trainer Charts (Last 5 Trainings) -->
    @if(count($evaluasiChartData) > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Hasil Evaluasi Trainer (5 Training Terakhir)</h3>
            
            @foreach($evaluasiChartData as $data)
                <div class="mb-8 last:mb-0 pb-8 last:pb-0 border-b last:border-b-0">
                    <div class="mb-4">
                        <h4 class="text-md font-semibold text-gray-700">{{ $data['training'] }}</h4>
                        <p class="text-sm text-gray-500">Trainer: {{ $data['trainer'] }} | Tanggal: {{ $data['tanggal'] }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($data['statistics'] as $aspect => $stats)
                            <div>
                                <h5 class="text-sm font-medium text-gray-700 mb-3 capitalize">
                                    {{ str_replace('_', ' ', $aspect) }}
                                </h5>
                                
                                <!-- Chart Bars -->
                                <div class="space-y-2">
                                    @foreach(['SB' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup', 'K' => 'Kurang'] as $indicator => $label)
                                        @php
                                            $percentage = $stats[$indicator]['percentage'];
                                            $count = $stats[$indicator]['count'];
                                            $colorClass = [
                                                'SB' => 'bg-green-500',
                                                'B' => 'bg-blue-500',
                                                'C' => 'bg-yellow-500',
                                                'K' => 'bg-red-500'
                                            ][$indicator];
                                        @endphp
                                        
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-xs font-medium text-gray-600">{{ $label }}</span>
                                                <span class="text-xs font-semibold text-gray-700">{{ $percentage }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-6 relative">
                                                <div class="{{ $colorClass }} h-6 rounded-full flex items-center justify-end pr-2" 
                                                     style="width: {{ $percentage }}%">
                                                    @if($percentage > 15)
                                                        <span class="text-xs font-semibold text-white">{{ $count }}</span>
                                                    @endif
                                                </div>
                                                @if($percentage <= 15 && $count > 0)
                                                    <span class="absolute right-2 top-0.5 text-xs font-semibold text-gray-700">{{ $count }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Evaluasi Atasan Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Kategori Evaluasi Atasan -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Evaluasi Atasan - Kategori</h3>
            <div class="space-y-3">
                @php
                    $totalEvalAtasan = array_sum($evaluasiAtasanKategori);
                @endphp
                
                @foreach(['Sangat Baik' => 'green', 'Baik' => 'blue', 'Cukup' => 'yellow', 'Perlu Perbaikan' => 'red'] as $kategori => $color)
                    @php
                        $count = $evaluasiAtasanKategori[$kategori] ?? 0;
                        $percentage = $totalEvalAtasan > 0 ? ($count / $totalEvalAtasan) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $kategori }}</span>
                            <span class="text-sm font-bold text-{{ $color }}-600">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-6">
                            <div class="bg-{{ $color }}-500 h-6 rounded-full flex items-center justify-center text-white text-xs font-semibold" 
                                 style="width: {{ $percentage }}%">
                                @if($percentage > 10)
                                    {{ number_format($percentage, 0) }}%
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Department Performance -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Performa per Departemen</h3>
            <div class="space-y-3">
                @forelse($departmentPerformance as $dept)
                    @php
                        $percentage = ($dept->avg_skor / 20) * 100;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $dept->department }}</span>
                            <span class="text-sm font-bold text-blue-600">{{ number_format($dept->avg_skor, 1) }}/20</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-6">
                            <div class="bg-blue-500 h-6 rounded-full flex items-center justify-center text-white text-xs font-semibold" 
                                 style="width: {{ $percentage }}%">
                                @if($percentage > 15)
                                    {{ $dept->jumlah }} evaluasi
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">Belum ada data evaluasi</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Top Performers (Berdasarkan Rata-rata Posttest)</h3>
            <span class="text-sm text-gray-500">Min. 2 training</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Departemen</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Training</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Avg Score</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($topPerformers as $index => $performer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-bold text-gray-900">
                                @if($index == 0)
                                    <i class="fas fa-crown text-yellow-500"></i>
                                @elseif($index == 1)
                                    <i class="fas fa-star text-gray-400"></i>
                                @elseif($index == 2)
                                    <i class="fas fa-star text-orange-400"></i>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $performer->nama }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $performer->department }}</td>
                            <td class="px-4 py-3 text-sm text-center">{{ $performer->total_training }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-lg font-bold text-green-600">{{ number_format($performer->avg_score, 1) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data performer
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Access</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('users.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition">
                <i class="fas fa-users text-blue-600 text-2xl mb-2"></i>
                <span class="text-sm text-gray-700 text-center">Manage Users</span>
            </a>
            <a href="{{ route('roles.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-purple-50 transition">
                <i class="fas fa-user-shield text-purple-600 text-2xl mb-2"></i>
                <span class="text-sm text-gray-700 text-center">Manage Roles</span>
            </a>
            <a href="{{ route('data-master.employees.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-green-50 transition">
                <i class="fas fa-id-card text-green-600 text-2xl mb-2"></i>
                <span class="text-sm text-gray-700 text-center">Employees</span>
            </a>
            <a href="{{ route('data-master.departments.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-yellow-50 transition">
                <i class="fas fa-building text-yellow-600 text-2xl mb-2"></i>
                <span class="text-sm text-gray-700 text-center">Departments</span>
            </a>
            <a href="{{ route('training.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-indigo-50 transition">
                <i class="fas fa-chalkboard-teacher text-indigo-600 text-2xl mb-2"></i>
                <span class="text-sm text-gray-700 text-center">Training</span>
            </a>
            <a href="{{ route('training-record.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-pink-50 transition">
                <i class="fas fa-history text-pink-600 text-2xl mb-2"></i>
                <span class="text-sm text-gray-700 text-center">Training Record</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">User Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentUsers as $user)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-800">{{ $user->role->display_name ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $user->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection