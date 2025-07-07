<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; 

class KontakWali extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['siswa_id', 'nama_wali', 'no_hp', 'status_hubungan'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}