<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'user_name', 'action', 'subject_type', 'subject_id',
        'subject_label', 'description', 'changes', 'ip_address', 'created_at',
    ];

    protected $casts = [
        'changes'    => 'array',
        'created_at' => 'datetime',
    ];

    /** Label terbaca untuk jenis subjek (Indonesia). */
    public const SUBJECT_LABELS = [
        'Cpl'           => 'CPL',
        'MataKuliah'    => 'Mata Kuliah',
        'BahanKajian'   => 'Bahan Kajian',
        'ProgramStudi'  => 'Program Studi',
        'ProfilLulusan' => 'Profil Lulusan',
        'CloTaxonomi'   => 'CLO Taxonomy',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSubjectLabelHumanAttribute(): string
    {
        return self::SUBJECT_LABELS[$this->subject_type] ?? $this->subject_type;
    }
}
