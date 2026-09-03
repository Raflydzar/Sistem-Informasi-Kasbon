<nav x-data="{ sidebarOpen: false }" class="bg-white border-b border-slate-100 dark:bg-slate-900 dark:border-slate-800 sticky top-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Sisi Kiri: Tombol Toggle & Logo Saja -->
            <div class="flex items-center gap-4">
                <!-- Tombol Toggle Sidebar -->
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none" title="Menu Sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-slate-800 dark:text-slate-200" />
                    </a>
                </div>
            </div>

            <!-- Sisi Kanan: Badge Nama User (Dropdown Profile) -->
            <div class="flex items-center relative" x-data="{ openProfile: false }">
                <button @click="openProfile = !openProfile" @click.away="openProfile = false" 
                        class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 transition text-sm font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <div class="shrink-0 w-7 h-7 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="whitespace-nowrap">{{ Auth::user()->name }}</span>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="openProfile" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 top-full mt-2 w-56 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-lg py-3 px-4 z-50">
                    
                    <!-- Info User -->
                    <div class="mb-3 px-1">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center justify-center px-3 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-3 py-2 text-xs font-medium text-white bg-rose-600 rounded-xl hover:bg-rose-500 transition">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SIDEBAR DRAWER -->
    <div x-cloak>
        <!-- Backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black/50 z-40"></div>

        <!-- Sidebar Panel -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-in-out duration-300 transform" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in-out duration-300 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full" 
             class="fixed inset-y-0 left-0 w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 shadow-xl z-50 flex flex-col">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between px-6 h-16 border-b border-slate-200 dark:border-slate-800">
                <span class="font-semibold text-base text-slate-800 dark:text-slate-100 tracking-wider">Sistem Informasi</span>
                <button @click="sidebarOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Sidebar Content / Links -->
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-1.5">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <!-- Manajemen Unit -->
                <a href="{{ route('unit.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('unit.*') ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Manajemen Unit
                </a>

                <!-- Dropdown Data Kasbon (Pengganti Jenis Transaksi & Petty Cash) -->
                <div x-data="{ openTransaksi: true }" class="space-y-1 pt-1">
                    <button @click="openTransaksi = !openTransaksi" class="flex items-center justify-between w-full px-4 py-2.5 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2"/></svg>
                            Data Transaksi Petty Cash
                        </span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="openTransaksi ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Daftar Sub-Kategori Kasbon -->
                    <div x-show="openTransaksi" x-transition class="pl-4 mt-1 space-y-0.5 border-l-2 border-slate-200 dark:border-slate-700 ms-6 pb-2">
                        
                        @php
                            $subItems = [
                                '' => 'Semua Kasbon',
                                'kasbon_umum' => 'Kasbon Umum (Lainnya)',
                                'building_material' => 'Building Material',
                                'fuel' => 'Fuel (BBM)',
                                'spare_part_vehicle' => 'Spare Part Vehicle',
                                'electrical' => 'Electrical',
                                'water' => 'Water',
                                'office_equipment' => 'Office Equipment',
                                'mess_equipment' => 'Mess Equipment',
                            ];
                        @endphp

                        @foreach($subItems as $key => $label)
                            @php
                                $isActive = request('sub_kategori') === $key || (empty($key) && !request()->has('sub_kategori'));
                            @endphp
                            <a href="{{ route('transaksi.index', array_filter(['sub_kategori' => $key])) }}" 
                               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition {{ $isActive ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400 font-semibold' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-blue-600 dark:bg-blue-400' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                                <span>{{ $label }}</span>
                            </a>
                        @endforeach
                        
                        <!-- Invoice Payment (Disabled) -->
                        <div class="flex items-center justify-between px-3 py-2 mt-2 rounded-xl text-xs font-medium text-slate-400 cursor-not-allowed opacity-60">
                            <span>Invoice Payment</span>
                            <span class="text-[9px] bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded font-mono">Soon</span>
                        </div>
                    </div>
                </div>

                <!-- Laporan Keuangan -->
                <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400 font-semibold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan
                </a>
            </div>
        </div>
    </div>
</nav>