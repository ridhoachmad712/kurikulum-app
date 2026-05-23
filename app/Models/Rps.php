<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rps extends Model
{
    protected $fillable = [
        'mata_kuliah_id','dosen_id','tahun_ajaran',
        'semester','status','submitted_at','approved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at'  => 'datetime',
    ];

    public function mataKuliah(): BelongsTo { return $this->belongsTo(MataKuliah::class); }
    public function dosen(): BelongsTo      { return $this->belongsTo(User::class, 'dosen_id'); }
}
