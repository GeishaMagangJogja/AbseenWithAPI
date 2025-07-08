@extends('layouts.app')

@section('title', 'Dashboard Absensi Siswa')

@section('content')
<div class="space-y-6">

    <!-- Statistik Absensi -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach (['Hadir', 'Sakit', 'Izin', 'Alpa'] as $status)
            <div class="bg-white rounded-lg shadow border p-4 text-center">
                <p class="text-sm font-semibold text-gray-500">{{ $status }}</p>
                <p class="mt-2 text-2xl font-bold text-gray-800">
                    {{ App\Models\Absensi::where('status', $status)->count() }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Daftar Absensi Hari Ini -->
    <div class="bg-white rounded-lg shadow border overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-1">
                📝 Daftar Absensi Hari Ini
            </h3>
            <a href="{{ route('absensi.create') }}"
               class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">
                + Tambah Absensi
            </a>
        </div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase">
                <tr>
                    <th class="px-4 py-3">NIS</th>
                    <th class="px-4 py-3">Nama Siswa</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Jam Masuk</th>
                    <th class="px-4 py-3">Wali</th>
                    <th class="px-4 py-3">Notifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($absensi as $absen)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $absen->siswa->nis }}</td>
                        <td class="px-4 py-3 font-medium">{{ $absen->siswa->nama_lengkap }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($absen->status == 'Hadir') bg-green-100 text-green-700
                                @elseif($absen->status == 'Sakit') bg-yellow-100 text-yellow-700
                                @elseif($absen->status == 'Izin') bg-blue-100 text-blue-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ $absen->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $absen->jam_masuk ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ $absen->siswa->kontakWali->nama_wali ?? '-' }}<br>
                            <span class="text-gray-500">{{ $absen->siswa->kontakWali->no_hp ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($absen->siswa->kontakWali)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Terkirim</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">No HP Kosong</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada data absensi hari ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Statistik Tambahan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white border rounded-lg p-4 shadow flex flex-col justify-between">
            <div>
                <p class="text-lg font-semibold text-blue-700">ℹ Total Siswa Terdaftar</p>
                <p class="mt-2 text-3xl font-bold">{{ App\Models\Siswa::count() }} Siswa</p>
            </div>
            <a href="#"
               class="mt-3 inline-block text-sm text-blue-600 hover:underline font-medium">
                Lihat Daftar Lengkap →
            </a>
        </div>
        <div class="bg-white border rounded-lg p-4 shadow">
            <p class="text-lg font-semibold text-green-700">✅ Kehadiran Hari Ini</p>
            <p class="mt-2 text-3xl font-bold">
                {{ App\Models\Absensi::whereDate('tanggal', today())->where('status', 'Hadir')->count() }} /
                {{ App\Models\Siswa::count() }}
            </p>
            <p class="text-sm mt-1 text-gray-600">
                @php
                    $total = App\Models\Siswa::count();
                    $hadir = App\Models\Absensi::whereDate('tanggal', today())->where('status', 'Hadir')->count();
                    $persentase = $total > 0 ? round(($hadir / $total) * 100) : 0;
                @endphp
                {{ $persentase }}% Kehadiran
            </p>
        </div>
    </div>
</div>
@endsection
