<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_hp',
        'jenis_kelamin',
        'foto',
        'bio',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
