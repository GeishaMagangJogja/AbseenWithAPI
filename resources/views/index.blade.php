@extends('layouts.app')

@section('title', 'Dashboard Absensi Siswa')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Kartu Statistik Absensi -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach (['Hadir', 'Sakit', 'Izin', 'Alpa'] as $status)
            <div class="bg-white rounded-xl text-center shadow p-4 border">
                <p class="text-sm font-semibold text-gray-600">{{ $status }}</p>
                <p class="text-lg text-gray-900 font-bold">
                    {{ App\Models\Absensi::where('status', $status)->count() }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Daftar Absensi Hari Ini -->
    <div class="bg-white rounded-xl shadow border overflow-auto">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">📝 Daftar Absensi Hari Ini</h3>
            <a href="{{ route('absensi.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                + Tambah Absensi
            </a>
        </div>
        <table class="min-w-full text-sm text-left divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2">NIS</th>
                    <th class="px-4 py-2">Nama Siswa</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Jam Masuk</th>
                    <th class="px-4 py-2">Wali</th>
                    <th class="px-4 py-2">Notifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($absensi as $absen)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $absen->siswa->nis }}</td>
                        <td class="px-4 py-2 font-medium">{{ $absen->siswa->nama_lengkap }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded-full text-xs
                                @if($absen->status == 'Hadir') bg-green-100 text-green-800
                                @elseif($absen->status == 'Sakit') bg-yellow-100 text-yellow-800
                                @elseif($absen->status == 'Izin') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $absen->status }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $absen->jam_masuk ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm">
                            {{ $absen->siswa->kontakWali->nama_wali ?? '-' }}<br>
                            <span class="text-gray-500">{{ $absen->siswa->kontakWali->no_hp ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-2">
                            @if($absen->siswa->kontakWali)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Terkirim</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">No HP Kosong</span>
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

    <!-- Statistik dan Informasi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-blue-50 border border-blue-400 rounded-xl p-4 text-blue-700 text-sm">
            <p class="font-semibold text-lg">ℹ Total Siswa Terdaftar</p>
            <p class="mt-2 text-2xl font-bold">{{ App\Models\Siswa::count() }} Siswa</p>
            <a href="#" class="inline-block mt-2 text-xs text-blue-800 underline font-semibold">Lihat Daftar Lengkap</a>
        </div>
        <div class="bg-green-50 border border-green-400 rounded-xl p-4 text-green-700 text-sm">
            <p class="font-semibold text-lg">✅ Kehadiran Hari Ini</p>
            <p class="mt-2 text-2xl font-bold">
                {{ App\Models\Absensi::whereDate('tanggal', today())->where('status', 'Hadir')->count() }} /
                {{ App\Models\Siswa::count() }}
            </p>
            <p class="text-sm mt-1">
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
