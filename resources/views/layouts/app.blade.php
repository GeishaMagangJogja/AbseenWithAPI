<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Absensi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header dengan Navigasi Dasar -->
    <header class="bg-blue-600 text-white shadow">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold">Absensi Sekolah</h1>
                <nav class="flex space-x-4">
                    <a href="{{ route('absensi.index') }}" class="px-3 py-1 rounded hover:bg-blue-700">
                        Daftar Absensi
                    </a>
                    <a href="{{ route('absensi.create') }}" class="px-3 py-1 rounded hover:bg-blue-700">
                        Input Absensi
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="container mx-auto p-4 mt-4">
        @yield('content')
    </main>

    <!-- Footer Minimal -->
    <footer class="bg-gray-100 border-t mt-8 py-4">
        <div class="container mx-auto text-center text-gray-600">
            Sistem Absensi &copy; {{ date('Y') }}
        </div>
    </footer>
</body>
</html>
