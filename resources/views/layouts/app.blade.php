<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Absensi Sekolah')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

    <header class="bg-slate-900 shadow-md text-white">
    <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">
        <h1 class="text-xl font-bold tracking-wide flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Absensi Sekolah
        </h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('absensi.index') }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-md text-sm font-medium border border-slate-700 bg-white text-slate-900 hover:bg-slate-200 transition shadow-sm">
                📋 Daftar Absensi
            </a>
            <a href="{{ route('absensi.create') }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-md text-sm font-medium bg-gradient-to-r from-emerald-500 to-green-600 text-white hover:from-emerald-600 hover:to-green-700 transition shadow-md">
                ➕ Input Absensi
            </a>
        </div>
    </div>
</header>

    <!-- Konten Utama -->
    <main class="flex-1 max-w-7xl mx-auto px-6 py-8 space-y-8">

        <!-- Judul -->
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Dashboard Absensi</h2>
            <p class="text-sm text-gray-500">Pantau kehadiran siswa secara real-time</p>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            @foreach(['Hadir' => 'green', 'Sakit' => 'yellow', 'Izin' => 'blue', 'Alpa' => 'red'] as $label => $color)
            <div class="bg-white rounded-xl shadow p-5 text-center border-t-4 border-{{ $color }}-500">
                <div class="text-sm text-gray-500">{{ $label }}</div>
                <div class="text-2xl font-bold text-gray-800">0</div>
            </div>
            @endforeach
        </div>

        <!-- Tabel Absensi -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">📑 Daftar Absensi Hari Ini</h3>
                <a href="{{ route('absensi.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded-md font-medium">+ Tambah Absensi</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-gray-600 font-medium">
                        <tr>
                            <th class="px-4 py-2">NIS</th>
                            <th class="px-4 py-2">Nama Siswa</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Jam Masuk</th>
                            <th class="px-4 py-2">Wali</th>
                            <th class="px-4 py-2">Notifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Belum ada data absensi hari ini</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white shadow rounded-xl p-6">
                <div class="text-gray-500 text-sm">Total Siswa Terdaftar</div>
                <div class="text-xl font-bold mt-1">0 Siswa</div>
                <a href="#" class="text-blue-600 text-sm hover:underline mt-2 inline-block">Lihat Daftar Lengkap →</a>
            </div>
            <div class="bg-white shadow rounded-xl p-6">
                <div class="text-green-600 font-medium flex items-center gap-1">
                    ✅ Kehadiran Hari Ini
                </div>
                <div class="text-xl font-bold mt-1">0 / 0</div>
                <div class="text-sm text-gray-500">0% Kehadiran</div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t text-center py-4 text-sm text-gray-500">
        &copy; {{ date('Y') }} Sistem Absensi Sekolah. All rights reserved.
    </footer>

</body>
</html>
