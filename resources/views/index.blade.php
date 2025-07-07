@extends('layouts.app')

@section('title', 'Dashboard Kehadiran')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Kartu Status Absensi -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach (['Masuk', 'Izin', 'Izin Kembali', 'Istirahat', 'Kembali', 'Pulang'] as $status)
            <div class="bg-white rounded-xl text-center shadow p-4 border">
                <p class="text-sm font-semibold text-gray-600">{{ $status }}</p>
                <p id="status-{{ strtolower(str_replace(' ', '-', $status)) }}" class="text-lg text-gray-900 font-bold">---</p>
            </div>
        @endforeach
    </div>

    <!-- Jadwal Mingguan -->
    <div class="bg-white rounded-xl shadow border overflow-auto">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">📅 Jadwalmu Minggu Ini</h3>
        </div>
        <table class="min-w-full text-sm text-left divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Hari</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Shift</th>
                    <th class="px-4 py-2">Masuk</th>
                    <th class="px-4 py-2">Pulang</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ([
                    ['09-07-2025', 'Rabu', 'WFO', 'Siang (13.00 - 21.00)', '13:00:00', '21:00:00'],
                    ['10-07-2025', 'Kamis', 'WFO', 'Siang (13.00 - 21.00)', '13:00:00', '21:00:00'],
                    ['11-07-2025', 'Jumat', 'WFO', 'Siang (13.00 - 21.00)', '13:00:00', '21:00:00'],
                    ['12-07-2025', 'Sabtu', 'WFO', 'Siang (13.00 - 21.00)', '13:00:00', '21:00:00']
                ] as $jadwal)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $jadwal[0] }}</td>
                        <td class="px-4 py-2">{{ $jadwal[1] }}</td>
                        <td class="px-4 py-2">{{ $jadwal[2] }}</td>
                        <td class="px-4 py-2">{{ $jadwal[3] }}</td>
                        <td class="px-4 py-2">{{ $jadwal[4] }}</td>
                        <td class="px-4 py-2">{{ $jadwal[5] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Attention Boxes -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-red-50 border border-red-400 rounded-xl p-4 text-red-600 text-sm">
            <p class="font-semibold text-lg">⚠ Attention!</p>
            <p class="mt-1">Tidak ada catatan</p>
        </div>
        <div class="bg-green-50 border border-green-400 rounded-xl p-4 text-green-700 text-sm">
            <p class="font-semibold text-lg">✅ Tidak Memiliki Kekurangan Kerja</p>
            <p class="text-sm mt-2">+ 0j 0m</p>
            <a href="#" class="inline-block mt-2 text-xs text-blue-800 underline font-semibold">Lihat Detail</a>
        </div>
    </div>

</div>
@endsection
