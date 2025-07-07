<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\KontakWali;

class SiswaDanWaliSeeder extends Seeder
{
    public function run()
    {
        // Data dummy siswa 1
        $siswa1 = Siswa::create([
            'nama_lengkap' => 'Andi Wijaya',
            'nis' => '2023001',
        ]);

        KontakWali::create([
            'siswa_id' => $siswa1->id,
            'nama_wali' => 'Wijaya Kusuma',
            'no_hp' => '6281234567890', // Format internasional
            'status_hubungan' => 'Ayah',
        ]);

        // Data dummy siswa 2
        $siswa2 = Siswa::create([
            'nama_lengkap' => 'Budi Santoso',
            'nis' => '2023002',
        ]);

        KontakWali::create([
            'siswa_id' => $siswa2->id,
            'nama_wali' => 'Santoso Hadi',
            'no_hp' => '6289876543210',
            'status_hubungan' => 'Ayah',
        ]);

        // Data dummy siswa 3
        $siswa3 = Siswa::create([
            'nama_lengkap' => 'Citra Dewi',
            'nis' => '2023003',
        ]);

        KontakWali::create([
            'siswa_id' => $siswa3->id,
            'nama_wali' => 'Dewi Lestari',
            'no_hp' => '6285678901234',
            'status_hubungan' => 'Ibu',
        ]);

        // Data dummy tambahan (10 siswa)
        for ($i = 4; $i <= 13; $i++) {
            $siswa = Siswa::create([
                'nama_lengkap' => 'Siswa Contoh ' . $i,
                'nis' => '2023' . str_pad($i, 3, '0', STR_PAD_LEFT),
            ]);

            KontakWali::create([
                'siswa_id' => $siswa->id,
                'nama_wali' => 'Orang Tua Siswa ' . $i,
                'no_hp' => '628' . rand(1000000000, 9999999999),
                'status_hubungan' => rand(0, 1) ? 'Ayah' : 'Ibu',
            ]);
        }
    }
}
