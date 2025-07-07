@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Tambah Data Absensi</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('absensi.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="siswa_id" class="block font-semibold mb-1">Nama Siswa</label>
            <select name="siswa_id" id="siswa_id" class="w-full border-gray-300 rounded p-2" required>
                <option value="">-- Pilih Siswa --</option>
                @foreach ($siswas as $siswa)
                    <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                        {{ $siswa->nama }} ({{ $siswa->kelas }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="tanggal" class="block font-semibold mb-1">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full border-gray-300 rounded p-2" required>
        </div>

        <div>
            <label for="status" class="block font-semibold mb-1">Status</label>
            <select name="status" id="status" class="w-full border-gray-300 rounded p-2" required>
                <option value="">-- Pilih Status --</option>
                <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ old('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="alpha" {{ old('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
            </select>
        </div>

        <div>
            <label for="keterangan" class="block font-semibold mb-1">Keterangan (Opsional)</label>
            <textarea name="keterangan" id="keterangan" class="w-full border-gray-300 rounded p-2" rows="3">{{ old('keterangan') }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan & Kirim WA
            </button>
            <a href="{{ route('absensi.index') }}" class="text-gray-600 hover:underline mt-2">Batal</a>
        </div>

        <div class="mt-4">
    @if (session('whatsapp_error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('whatsapp_error') }}
        </div>
    @endif
</div>
    </form>
</div>
@endsection
