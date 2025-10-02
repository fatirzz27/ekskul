<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_ekskul',
        'deskripsi',
        'foto',
    ];

    // helper untuk URL foto
    public function getFotoUrlAttribute()
    {
        return asset('images/ekskul/' . ($this->foto ?: 'default.jpg'));
    }

    /**
     * Anggota ekskul (users) many-to-many.
     */
    public function anggota()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }





public function pembina()
{
    return $this->belongsToMany(User::class, 'pembina_ekskul', 'ekskul_id', 'user_id');
}

    public function pengumumans()
{
    return $this->hasMany(Pengumuman::class);
}
}  