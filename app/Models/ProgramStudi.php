<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\LogsActivity;

class ProgramStudi extends Model
{
    use LogsActivity;

    protected $fillable = ['kode','nama','jenjang','fakultas','universitas','aktif','visi','misi','tujuan','alamat','email','telepon','website'];

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

    public function profilLulusans(): HasMany
    {
        return $this->hasMany(ProfilLulusan::class)->orderBy('urutan');
    }

    public function plos(): HasMany
    {
        return $this->hasMany(Plo::class)->orderBy('urutan');
    }
}
