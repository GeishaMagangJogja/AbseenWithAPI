<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Siswa extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['nama_lengkap', 'nis'];

    public function kontakWali()
    {
        return $this->hasOne(KontakWali::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
