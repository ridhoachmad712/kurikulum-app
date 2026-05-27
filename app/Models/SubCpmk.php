<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubCpmk extends Model
{
    protected $fillable = [
        'mata_kuliah_id', 'kode', 'deskripsi',
        'kode_clo_tertinggi', 'urutan',
    ];

    /** Sub-CPMK dapat terhubung ke banyak CPMK dalam MK yang sama */
    public function cpmks(): BelongsToMany
    {
        return $this->belongsToMany(Cpmk::class, 'cpmk_sub_cpmk')
            ->withPivot('bobot')
            ->withTimestamps();
    }

    public function mataKuliah(): BelongsTo { return $this->belongsTo(MataKuliah::class); }

    public function cloTaxonomis(): BelongsToMany
    {
        return $this->belongsToMany(CloTaxonomi::class, 'sub_cpmk_clos', 'sub_cpmk_id', 'clo_id')
            ->withPivot('is_tertinggi')
            ->withTimestamps();
    }

    public function subCpmkClos(): HasMany
    {
        return $this->hasMany(SubCpmkClo::class);
    }

    public function asesmen(): HasOne
    {
        return $this->hasOne(SubCpmkAsesmen::class);
    }
}
