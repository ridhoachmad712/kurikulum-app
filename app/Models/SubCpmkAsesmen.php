<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCpmkAsesmen extends Model
{
    protected $fillable = ['sub_cpmk_id','aktivitas_partisipatif','tugas','quis','kasus_proyek','uts','uas'];
    protected $casts    = [
        'aktivitas_partisipatif' => 'decimal:2',
        'tugas'        => 'decimal:2',
        'quis'         => 'decimal:2',
        'kasus_proyek' => 'decimal:2',
        'uts'          => 'decimal:2',
        'uas'          => 'decimal:2',
    ];

    public function subCpmk(): BelongsTo { return $this->belongsTo(SubCpmk::class); }

    public function getTotalAttribute(): float
    {
        return (float)$this->aktivitas_partisipatif + $this->tugas + $this->quis + $this->kasus_proyek + $this->uts + $this->uas;
    }
}
