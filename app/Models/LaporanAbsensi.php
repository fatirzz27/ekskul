<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAbsensi extends Model
{
    use HasFactory;

     protected $table = 'absensi'; // masih pakai tabel absensi
    protected $fillable = [
        'ekskul_id',
        'user_id',
        'tanggal',
        'status',
        'keterangan',
    ];

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class, 'ekskul_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
