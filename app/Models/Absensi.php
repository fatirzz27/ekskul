<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $fillable = [
        'ekskul_id', 'user_id', 'tanggal', 'status', 'keterangan'
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class, 'ekskul_id');
    }
}
