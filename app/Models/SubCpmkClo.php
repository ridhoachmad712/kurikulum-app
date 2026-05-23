<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCpmkClo extends Model
{
    protected $fillable = ['sub_cpmk_id','clo_id','is_tertinggi'];
    protected $casts    = ['is_tertinggi' => 'boolean'];

    public function subCpmk(): BelongsTo    { return $this->belongsTo(SubCpmk::class); }
    public function cloTaxonomi(): BelongsTo { return $this->belongsTo(CloTaxonomi::class, 'clo_id'); }
}
