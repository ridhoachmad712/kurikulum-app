<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\LogsActivity;

class ProfilLulusan extends Model
{
    use LogsActivity;

    protected $fillable = ['program_studi_id', 'kode', 'nama', 'deskripsi', 'urutan'];

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }
}
