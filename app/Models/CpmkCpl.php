<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CpmkCpl extends Model
{
    protected $fillable = ['cpmk_id','cpl_id','has_relation','bobot_persen'];

    protected $casts = ['has_relation' => 'boolean', 'bobot_persen' => 'decimal:2'];

    public function cpmk(): BelongsTo { return $this->belongsTo(Cpmk::class); }
    public function cpl(): BelongsTo  { return $this->belongsTo(Cpl::class); }
}
