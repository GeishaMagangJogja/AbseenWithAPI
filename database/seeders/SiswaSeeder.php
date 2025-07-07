<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = [
            [
                'nama' => 'Rudi Hartono',
                'nis' => '1234567890',
                'kelas' => '10 IPA 1',
                'nomor_wa_ortu' => '6282241279426', // Format internasional untuk 082241279426
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Siti Aminah',
                'nis' => '0987654321',
                'kelas' => '10 IPA 2',
                'nomor_wa_ortu' => '6281327659373', // Nomor contoh lain
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($siswas as $siswa) {
            // Validasi format nomor WhatsApp
            $nomor = $siswa['nomor_wa_ortu'];

            // Cek format nomor Indonesia (dimulai dengan 62 dan panjang 12-15 digit)
            if (!preg_match('/^62[0-9]{9,13}$/', $nomor)) {
                $this->command->error("Nomor WhatsApp tidak valid: {$nomor}");
                continue;
            }

            // Validasi khusus untuk nomor Indonesia yang valid
            $nomorTanpa62 = substr($nomor, 2); // Hilangkan 62 di depan
            if (!preg_match('/^8[0-9]{8,11}$/', $nomorTanpa62)) {
                $this->command->error("Nomor WhatsApp Indonesia tidak valid: {$nomor}");
                continue;
            }

            // Cek apakah siswa sudah ada berdasarkan NIS
            $existingSiswa = Siswa::where('nis', $siswa['nis'])->first();
            if ($existingSiswa) {
                $this->command->info("Siswa dengan NIS {$siswa['nis']} sudah ada, akan diupdate.");
                $existingSiswa->update($siswa);
            } else {
                Siswa::create($siswa);
                $this->command->info("Siswa {$siswa['nama']} berhasil ditambahkan dengan nomor WA: {$nomor}");
            }
        }

        // Tampilkan info format nomor
        $this->command->info("=== INFO FORMAT NOMOR ===");
        $this->command->info("Nomor 082241279426 akan diformat menjadi: 6282241279426");
        $this->command->info("Format yang disimpan di database: 6282241279426");
    }
}
