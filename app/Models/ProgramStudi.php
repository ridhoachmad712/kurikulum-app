<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudi extends Model
{
    protected $fillable = ['kode','nama','jenjang','fakultas','universitas','aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function cpls(): HasMany
    {
        return $this->hasMany(Cpl::class)->orderBy('urutan');
    }

    public function mataKuliahs(): HasMany
    {
        return $this->hasMany(MataKuliah::class)->orderBy('urutan');
    }

    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
