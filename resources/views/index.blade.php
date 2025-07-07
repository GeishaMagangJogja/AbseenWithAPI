@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Data Absensi Siswa</h1>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('absensi.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Absensi</a>

    <table class="w-full mt-6 border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Nama</th>
                <th class="border p-2">Tanggal</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Keterangan</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absensis as $absensi)
                <tr class="border-b">
                    <td class="border p-2">{{ $absensi->siswa->nama }}</td>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d-m-Y') }}</td>
                    <td class="border p-2 capitalize">{{ $absensi->status }}</td>
                    <td class="border p-2">{{ $absensi->keterangan ?? '-' }}</td>
                    <td class="border p-2 flex gap-2">
                        <a href="{{ route('absensi.edit', $absensi->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('absensi.destroy', $absensi->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data absensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
