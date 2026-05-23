<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait DosenMkScope
{
    protected function applyDosenScope(Builder $query): Builder
    {
        if (auth()->user()->hasRole('dosen')) {
            $query->whereHas('dosens', fn($d) => $d->where('users.id', auth()->id()));
        }
        return $query;
    }

    protected function authorizeDosenMk(\App\Models\MataKuliah $mk): void
    {
        abort_if(
            auth()->user()->hasRole('dosen') &&
            !$mk->dosens()->where('users.id', auth()->id())->exists(),
            403
        );
    }
}
