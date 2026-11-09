<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departemen extends Model
{
    protected $table = 'departemen';

    protected $fillable = ['nama'];

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'departemen_id');
    }

    public function dosen(): HasMany
    {
        return $this->hasMany(Dosen::class, 'departemen_id');
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'departemen_id');
    }
}
