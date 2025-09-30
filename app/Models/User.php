<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Profile;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang bisa diisi mass-assignment
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // tambahkan supaya bisa diisi
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting field tertentu
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        
    ];

    /**
     * Relasi ke tabel profiles (1 user punya 1 profile)
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    /**
     * Helper untuk cek role user
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPembina()
    {
        return $this->role === 'pembina';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    /**
     * Relasi many-to-many ke ekskul yang diikuti user (anggota).
     */
    public function ekskuls()
    {
        return $this->belongsToMany(Ekskul::class)->withTimestamps();
    }

    public function ekskul()
{
    return $this->belongsToMany(Ekskul::class, 'pembina_ekskul', 'user_id', 'ekskul_id');
}


}
