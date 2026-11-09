<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $fillable = ['judul', 'mata_kuliah', 'departemen_id', 'tenggat'];

    protected function casts(): array
    {
        return [
            'tenggat' => 'date',
        ];
    }

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    /**
     * Tugas masih aktif selama tenggatnya belum lewat (sampai akhir hari tenggat).
     */
    public function isAktif(): bool
    {
        return $this->tenggat->copy()->endOfDay()->isFuture();
    }
}
