<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    
    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'id_pembuat',
        'ekskul_id',
        'tanggal',
    ];

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class);
    }

    public function pembuat()
{
    return $this->belongsTo(\App\Models\User::class, 'id_pembuat');
}
}
