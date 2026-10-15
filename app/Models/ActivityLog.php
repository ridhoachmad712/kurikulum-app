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
        'Plo'           => 'PLO',
        'CloTaxonomi'   => 'CLO Taxonomy',
        'Akun'          => 'Akun',
        'Autentikasi'   => 'Autentikasi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Catat aktivitas manual (non-model) — mis. login, perubahan akun. */
    public static function record(
        string $action,
        string $description,
        ?string $subjectType = null,
        $subjectId = null,
        ?string $subjectLabel = null,
        ?int $userId = null,
        ?string $userName = null,
    ): void {
        $actor = auth()->user();
        static::create([
            'user_id'       => $userId ?? $actor?->id,
            'user_name'     => $userName ?? $actor?->name ?? 'Sistem',
            'action'        => $action,
            'subject_type'  => $subjectType,
            'subject_id'    => $subjectId,
            'subject_label' => $subjectLabel,
            'description'   => $description,
            'ip_address'    => request()->ip(),
            'created_at'    => now(),
        ]);
    }

    public function getSubjectLabelHumanAttribute(): string
    {
        return self::SUBJECT_LABELS[$this->subject_type] ?? $this->subject_type;
    }
}
