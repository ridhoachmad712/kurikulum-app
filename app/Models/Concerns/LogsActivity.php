<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Support\Str;

/**
 * Catat otomatis perubahan model (created / updated / deleted) ke activity_logs.
 * Hanya untuk data master. Pasang dengan `use LogsActivity;` pada model.
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn ($model) => $model->recordActivity('created'));
        static::updated(fn ($model) => $model->recordActivity('updated'));
        static::deleted(fn ($model) => $model->recordActivity('deleted'));
    }

    public function recordActivity(string $action): void
    {
        // Hanya catat bila ada user (lewati seeder/console tanpa auth)
        $user = auth()->user();

        $changes = null;
        if ($action === 'updated') {
            $changes = collect($this->getChanges())
                ->except(['updated_at', 'created_at'])
                ->keys()
                ->values()
                ->all();
            if (empty($changes)) return; // tidak ada perubahan berarti
        }

        ActivityLog::create([
            'user_id'       => $user?->id,
            'user_name'     => $user?->name ?? 'Sistem',
            'action'        => $action,
            'subject_type'  => class_basename($this),
            'subject_id'    => $this->getKey(),
            'subject_label' => $this->activityLabel(),
            'description'   => $this->activityDescription($action),
            'changes'       => $changes,
            'ip_address'    => request()->ip(),
            'created_at'    => now(),
        ]);
    }

    /** Label terbaca untuk record ini. */
    protected function activityLabel(): string
    {
        foreach (['kode_rps', 'kode', 'nama', 'judul'] as $attr) {
            if (!empty($this->{$attr})) {
                return (string) Str::limit($this->{$attr}, 60);
            }
        }
        return '#' . $this->getKey();
    }

    protected function activityDescription(string $action): string
    {
        $type  = ActivityLog::SUBJECT_LABELS[class_basename($this)] ?? class_basename($this);
        $verb  = ['created' => 'menambahkan', 'updated' => 'mengubah', 'deleted' => 'menghapus'][$action] ?? $action;
        return "{$verb} {$type} \"{$this->activityLabel()}\"";
    }
}
