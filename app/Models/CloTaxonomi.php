<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CloTaxonomi extends Model
{
    protected $fillable = ['kode','domain','level','deskripsi'];

    public function subCpmks(): BelongsToMany
    {
        return $this->belongsToMany(SubCpmk::class, 'sub_cpmk_clos', 'clo_id', 'sub_cpmk_id')
            ->withPivot('is_tertinggi')
            ->withTimestamps();
    }
}
