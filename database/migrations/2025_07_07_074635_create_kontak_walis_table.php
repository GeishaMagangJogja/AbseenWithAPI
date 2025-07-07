<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kontak_walis', function (Blueprint $table) {
            $table->id();
            // Foreign key yang terhubung ke tabel siswa.
            // onDelete('cascade') berarti jika siswa dihapus, data kontak ini juga ikut terhapus.
            $table->foreignId('siswa_id')->constrained()->onDelete('cascade');
            $table->string('nama_wali');
            $table->string('no_hp');
            $table->string('status_hubungan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontak_walis');
    }
};
