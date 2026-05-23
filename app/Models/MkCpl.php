<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MkCpl extends Model
{
    protected $fillable = ['mata_kuliah_id','cpl_id','level'];

    protected $appends = ['nilai_numerik'];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function cpl(): BelongsTo
    {
        return $this->belongsTo(Cpl::class);
    }

    public function getNilaiNumerikAttribute(): int
    {
        return match($this->level) {
            'Tinggi' => 5,
            'Sedang' => 3,
            'Rendah' => 1,
            default  => 0,
        };
    }

    public function getIsRelatedAttribute(): bool
    {
        return $this->level !== null;
    }
}
