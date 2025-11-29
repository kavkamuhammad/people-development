<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tailwind CSS & AlpineJS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100">

    @php
        // Tentukan menu default terbuka berdasarkan route
        $currentMenu =
            request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'user-management' :
            (request()->routeIs('data-master.*') || request()->routeIs('trainers.*') || request()->routeIs('materi-trainings.*') ? 'data-master' :
            (request()->routeIs('training.*') || request()->routeIs('evaluasi-*') || request()->routeIs('training-record.*') ? 'training-management' : ''));
    @endphp

    <div x-data="{ sidebarOpen: true, openMenu: '{{ $currentMenu }}' }" class="min-h-screen flex">

        <!-- SIDEBAR -->
<aside :class="sidebarOpen ? 'w-64' : 'w-20'"
       class="bg-slate-800 text-white transition-all duration-300 flex flex-col">

    <!-- SIDEBAR HEADER -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-slate-700">

        <!-- LOGO + TEXT -->
<div class="flex items-center space-x-3">
    <!-- Logo (hilang saat minimize) -->
    <img src="{{ asset('images/logo-RPA2.png') }}"
         alt="Logo"
         class="w-10 h-10 transition-all duration-300"
         x-show="sidebarOpen">

    <!-- Text (hilang saat minimize) -->
    <span x-show="sidebarOpen"
          class="text-xl font-bold whitespace-nowrap">
        HRM System
    </span>
</div>


        <!-- Toggle Button -->
        <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded hover:bg-slate-700">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- NAVIGATION -->
    <nav class="flex-1 py-4 overflow-y-auto">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 py-3 hover:bg-slate-700
           {{ request()->routeIs('dashboard') ? 'bg-slate-700 border-l-4 border-blue-500' : '' }}">
            <i class="fas fa-home w-8 text-center"></i>
            <span x-show="sidebarOpen" class="ml-2">Dashboard</span>
        </a>

        <!-- ========================= -->
        <!-- USER MANAGEMENT -->
        <!-- ========================= -->
        <div>
            <button @click="openMenu = openMenu === 'user-management' ? '' : 'user-management'"
                    class="w-full flex items-center justify-between px-4 py-3 hover:bg-slate-700">

                <div class="flex items-center">
                    <i class="fas fa-users-cog w-8 text-center"></i>
                    <span x-show="sidebarOpen" class="ml-2">User Management</span>
                </div>

                <i x-show="sidebarOpen"
                   :class="openMenu === 'user-management' ? 'rotate-90' : ''"
                   class="fas fa-chevron-right text-sm transition-transform"></i>
            </button>

            <div x-show="openMenu === 'user-management' && sidebarOpen"
                 x-collapse class="bg-slate-900">

                <a href="{{ route('users.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('users.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-user w-6 text-center"></i>
                    <span class="ml-2">User</span>
                </a>

                <a href="{{ route('roles.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('roles.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-user-shield w-6 text-center"></i>
                    <span class="ml-2">Role</span>
                </a>
            </div>
        </div>

        <!-- ========================= -->
        <!-- DATA MASTER -->
        <!-- ========================= -->
        <div>
            <button @click="openMenu = openMenu === 'data-master' ? '' : 'data-master'"
                    class="w-full flex items-center justify-between px-4 py-3 hover:bg-slate-700">

                <div class="flex items-center">
                    <i class="fas fa-database w-8 text-center"></i>
                    <span x-show="sidebarOpen" class="ml-2">Data Master</span>
                </div>

                <i x-show="sidebarOpen"
                   :class="openMenu === 'data-master' ? 'rotate-90' : ''"
                   class="fas fa-chevron-right text-sm transition-transform"></i>
            </button>

            <div x-show="openMenu === 'data-master' && sidebarOpen"
                 x-collapse class="bg-slate-900">

                <a href="{{ route('data-master.employees.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('data-master.employees.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-id-card w-6 text-center"></i>
                    <span class="ml-2">Karyawan</span>
                </a>

                <a href="{{ route('data-master.departments.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('data-master.departments.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-building w-6 text-center"></i>
                    <span class="ml-2">Departemen</span>
                </a>

                <a href="{{ route('data-master.job-levels.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('data-master.job-levels.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-layer-group w-6 text-center"></i>
                    <span class="ml-2">Level Jabatan</span>
                </a>

                <!-- TRAINER -->
                <a href="{{ route('trainers.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('trainers.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-chalkboard-teacher w-6 text-center"></i>
                    <span class="ml-2">Trainer</span>
                </a>

                <!-- MATERI TRAINING -->
                <a href="{{ route('materi-trainings.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('materi-trainings.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-book-open w-6 text-center"></i>
                    <span class="ml-2">Materi Training</span>
                </a>
            </div>
        </div>

        <!-- ========================= -->
        <!-- TRAINING MANAGEMENT -->
        <!-- ========================= -->
        <div>
            <button @click="openMenu = openMenu === 'training-management' ? '' : 'training-management'"
                    class="w-full flex items-center justify-between px-4 py-3 hover:bg-slate-700">

                <div class="flex items-center">
                    <i class="fas fa-graduation-cap w-8 text-center"></i>
                    <span x-show="sidebarOpen" class="ml-2">Training</span>
                </div>

                <i x-show="sidebarOpen"
                   :class="openMenu === 'training-management' ? 'rotate-90' : ''"
                   class="fas fa-chevron-right text-sm transition-transform"></i>
            </button>

            <div x-show="openMenu === 'training-management' && sidebarOpen"
                 x-collapse class="bg-slate-900">

                <!-- Data Training -->
                <a href="{{ route('training.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('training.*') && !request()->routeIs('training-record.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-clipboard-list w-6 text-center"></i>
                    <span class="ml-2">Data Training</span>
                </a>

                <!-- Evaluasi Trainer -->
                <a href="{{ route('evaluasi-trainer.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('evaluasi-trainer.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-star w-6 text-center"></i>
                    <span class="ml-2">Evaluasi Trainer</span>
                </a>

                <!-- Evaluasi Atasan -->
                <a href="{{ route('evaluasi-atasan.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('evaluasi-atasan.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-tasks w-6 text-center"></i>
                    <span class="ml-2">Evaluasi Atasan</span>
                </a>

                <!-- Training Record -->
                <a href="{{ route('training-record.index') }}"
                   class="flex items-center pl-12 py-2 hover:bg-slate-700
                   {{ request()->routeIs('training-record.*') ? 'text-blue-400' : '' }}">
                    <i class="fas fa-file-alt w-6 text-center"></i>
                    <span class="ml-2">Training Record</span>
                </a>
            </div>
        </div>

    </nav>

    <!-- USER INFO -->
    <div class="border-t border-slate-700 p-4">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center">
                <span class="text-white font-bold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
            </div>

            <div x-show="sidebarOpen" class="ml-3">
                <p class="text-sm font-medium">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-xs text-slate-400">
                    {{ Auth::user()->role->display_name ?? 'No Role' }}
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-3" x-show="sidebarOpen">
            @csrf
            <button type="submit"
                class="w-full text-left text-sm text-red-400 hover:text-red-300">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
        </form>
    </div>
</aside>


        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col">

            <!-- PAGE HEADER -->
            <header class="h-16 bg-white shadow flex items-center justify-between px-6">
                <h1 class="text-xl font-semibold text-gray-800">@yield('page-title')</h1>
                <span class="text-sm text-gray-600">{{ now()->format('l, d M Y') }}</span>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 p-6 overflow-y-auto">

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')

</body>
</html>