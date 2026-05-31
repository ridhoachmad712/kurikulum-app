<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Concerns\LogsActivity;

class BahanKajian extends Model
{
    use LogsActivity;

    protected $fillable = [
        'program_studi_id',
        'kode',
        'nama',
        'deskripsi',
        'urutan',
    ];

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function cpls(): BelongsToMany
    {
        return $this->belongsToMany(Cpl::class, 'cpl_bahan_kajian')
            ->withPivot('has_relation')
            ->withTimestamps();
    }

    public function mataKuliahs(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliah::class, 'mk_bahan_kajian')
            ->withPivot('has_relation')
            ->withTimestamps();
    }
}
