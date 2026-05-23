<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cpl extends Model
{
    protected $fillable = ['program_studi_id','kode','deskripsi','keterangan','urutan'];

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function mataKuliahs(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliah::class, 'mk_cpls')
            ->withPivot('level')
            ->withTimestamps();
    }

    public function mkCpls(): HasMany
    {
        return $this->hasMany(MkCpl::class);
    }

    public function cpmkCpls(): HasMany
    {
        return $this->hasMany(CpmkCpl::class);
    }
}
