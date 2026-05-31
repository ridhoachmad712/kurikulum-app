<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Concerns\LogsActivity;

class CloTaxonomi extends Model
{
    use LogsActivity;

    protected $fillable = ['kode','domain','level','deskripsi'];

    public function subCpmks(): BelongsToMany
    {
        return $this->belongsToMany(SubCpmk::class, 'sub_cpmk_clos', 'clo_id', 'sub_cpmk_id')
            ->withPivot('is_tertinggi')
            ->withTimestamps();
    }
}
