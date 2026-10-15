<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Concerns\LogsActivity;

class MataKuliah extends Model
{
    use LogsActivity;

    protected $fillable = [
        'program_studi_id','kode_rps','nama','sks',
        'semester','konsentrasi','is_pilihan','aktif','urutan','deskripsi',
    ];

    protected $casts = ['aktif' => 'boolean', 'is_pilihan' => 'boolean'];

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function cpls(): BelongsToMany
    {
        return $this->belongsToMany(Cpl::class, 'mk_cpls')
            ->withPivot('level')
            ->withTimestamps();
    }

    public function mkCpls(): HasMany
    {
        return $this->hasMany(MkCpl::class);
    }

    public function cpmks(): HasMany
    {
        return $this->hasMany(Cpmk::class)->orderBy('urutan');
    }

    public function subCpmks(): HasMany
    {
        return $this->hasMany(SubCpmk::class)->orderBy('urutan');
    }

    public function dosens(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'mk_dosens')->withTimestamps();
    }

    public function rps(): HasMany
    {
        return $this->hasMany(Rps::class);
    }

    public function rpsDocument(): HasOne
    {
        return $this->hasOne(RpsDocument::class);
    }

    public function bahanKajians(): BelongsToMany
    {
        return $this->belongsToMany(BahanKajian::class, 'mk_bahan_kajian')
            ->withPivot('has_relation')
            ->withTimestamps();
    }

    // --- computed helpers ---
    public function levelToNilai(?string $level): int
    {
        return match($level) {
            'Tinggi' => 5,
            'Sedang' => 3,
            'Rendah' => 1,
            default  => 0,
        };
    }
}
