<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function subCpmks(): HasMany
    {
        return $this->hasMany(SubCpmk::class)->orderBy('urutan');
    }
}
