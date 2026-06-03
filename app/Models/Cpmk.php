<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // tetap dipakai untuk cpmkCpls
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cpmk extends Model
{
    protected $fillable = ['mata_kuliah_id','kode','deskripsi','urutan'];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function cpls(): BelongsToMany
    {
        return $this->belongsToMany(Cpl::class, 'cpmk_cpls')
            ->withPivot('has_relation','bobot_persen')
            ->withTimestamps();
    }

    public function cpmkCpls(): HasMany
    {
        return $this->hasMany(CpmkCpl::class);
    }

    /** Asesmen di level CPMK (sumber kebenaran input Bobot Asesmen) */
    public function asesmen(): HasOne
    {
        return $this->hasOne(CpmkAsesmen::class);
    }

    /** CPMK dapat terhubung ke banyak Sub-CPMK dalam MK yang sama */
    public function subCpmks(): BelongsToMany
    {
        return $this->belongsToMany(SubCpmk::class, 'cpmk_sub_cpmk')
            ->withPivot('bobot')
            ->withTimestamps()
            ->orderBy('sub_cpmks.urutan');
    }
}
