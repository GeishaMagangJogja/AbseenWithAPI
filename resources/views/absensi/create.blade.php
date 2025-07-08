<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-slate-900 text-white shadow">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-semibold tracking-tight">Absensi Sekolah</h1>
            <nav class="flex gap-3">
                <a href="{{ route('absensi.index') }}"
                   class="px-4 py-2 rounded-md text-sm font-medium bg-white text-slate-900 border hover:bg-slate-800 hover:text-white transition">
                    📋 Daftar Absensi
                </a>
            </nav>
        </div>
    </header>

    <!-- Form Input -->
    <main class="flex-1 flex justify-center items-center py-8 px-4">
        <div class="w-full max-w-xl bg-white p-6 rounded-2xl shadow-lg space-y-6 border border-gray-100">
            <div class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                📝 Form Absensi Siswa
            </div>
            <form method="POST" action="{{ route('absensi.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Nama Siswa <span class="text-red-500">*</span></label>
                    <select name="siswa_id" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-slate-700">
                        <option value="">-- Pilih Siswa --</option>
                        {{-- @foreach($siswas as $siswa) --}}
                        {{-- <option value="{{ $siswa->id }}">{{ $siswa->nama }}</option> --}}
                        {{-- @endforeach --}}
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-slate-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-slate-700">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status Kehadiran <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-slate-700">
                        <option value="">-- Pilih Status --</option>
                        <option value="hadir">Hadir</option>
                        <option value="sakit">Sakit</option>
                        <option value="izin">Izin</option>
                        <option value="alpa">Alpa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-slate-700"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <a href="{{ route('absensi.index') }}"
                       class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-sm font-medium text-gray-700 transition">Batal</a>
                    <button type="submit"
                        class="px-5 py-2 rounded-md bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold shadow-md transition">
                        ✅ Simpan & Kirim WA
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t text-center py-4 text-sm text-gray-500">
        &copy; {{ date('Y') }} Sistem Absensi Sekolah
    </footer>

</body>
</html>
