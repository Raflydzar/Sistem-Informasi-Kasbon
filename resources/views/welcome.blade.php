<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('mci.png') }}">
    <title>Sistem Informasi Kasbon</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Open Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between p-6">

    <!-- Top Navigation -->
    <header class="w-full max-w-6xl mx-auto flex items-center justify-between py-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center font-bold text-white shadow-lg shadow-blue-500/30">
                K
            </div>
            <span class="font-semibold text-lg tracking-tight">Kasbon App</span>
        </div>

        @if (Route::has('login'))
            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition shadow-md">
                        Buka Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition shadow-md shadow-blue-600/20">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <!-- Hero Content -->
    <main class="w-full max-w-4xl mx-auto my-auto py-12 text-center flex flex-col items-center">
        <span class="px-3.5 py-1 text-xs font-semibold text-blue-400 bg-blue-500/10 border border-blue-500/20 rounded-full mb-6 inline-block">
            Sistem Informasi Manajemen Keuangan
        </span>

        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-white max-w-2xl leading-tight mb-4">
            Kelola Pengajuan Kasbon Lebih Cepat & Transparan
        </h1>

        <p class="text-slate-400 text-base md:text-lg max-w-xl mb-8">
            Platform digital pencatatan, verifikasi, dan monitoring pinjaman kasbon karyawan secara real-time dan terstruktur.
        </p>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
            @auth
                <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl transition shadow-lg shadow-blue-500/30">
                    Menuju Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl transition shadow-lg shadow-blue-500/30">
                    Masuk ke Akun
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-200 font-medium rounded-xl transition">
                        Daftar Akun Baru
                    </a>
                @endif
            @endauth
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-6xl mx-auto text-center py-4 text-xs text-slate-500 border-t border-slate-900">
        &copy; {{ date('Y') }} Sistem Informasi Kasbon. All rights reserved.
    </footer>

</body>
</html>