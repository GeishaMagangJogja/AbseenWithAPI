<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Magang')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Header -->
    <header class="bg-gray-900 text-white px-6 py-4 shadow">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold">Abistanata Parahita</h1>
                <p class="text-sm text-gray-300">MJ/PROG/SMK TELKOM PWT/JUNI 2025/04</p>
            </div>
            <div class="text-sm text-gray-400 hidden md:block">
                Dashboard Kehadiran Magang
            </div>
        </div>
    </header>

    <!-- Layout -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r px-4 py-6 shadow hidden lg:block">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Shift Siang (13.00 - 21.00)</h2>
            <nav class="space-y-3">
                @foreach(['Masuk','Istirahat','Izin Keluar','Izin Kembali','Kembali','Pulang','+ Log Activity','History Log Activity','Hari Libur','Pengumuman'] as $menu)
                    <a href="#" class="block bg-gray-800 text-white text-center py-2 rounded-lg hover:bg-gray-700 font-semibold transition">
                        {{ $menu }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
