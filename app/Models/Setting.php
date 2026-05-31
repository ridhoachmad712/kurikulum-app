<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Throwable;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** Nilai default seluruh pengaturan. */
    public const DEFAULTS = [
        'app_name'               => 'Sistem Informasi Kurikulum',
        'institution_name'       => 'S1 Manajemen · FEB UNM',
        'footer_text'            => 'This App developed with love and coffee by',
        'footer_credit_name'     => '@ridhoachmad_',
        'footer_credit_url'      => 'https://instagram.com/ridhoachmad_',
        'footer_year'            => '2026',
        'maintenance_mode'       => '0',
        'break_reminder_enabled' => '1',
        'break_reminder_min'     => '20',
        'break_reminder_max'     => '30',
        'announcement_enabled'   => '0',
        'announcement_text'      => '',
        'announcement_type'      => 'info',   // info | warning
    ];

    /** Ambil semua pengaturan (cached), gabung dengan default. */
    public static function all($columns = ['*'])
    {
        try {
            $stored = Cache::rememberForever('app_settings', function () {
                return static::query()->pluck('value', 'key')->all();
            });
        } catch (Throwable $e) {
            $stored = []; // tabel belum ada (sebelum migrate) — pakai default
        }
        return array_merge(static::DEFAULTS, $stored);
    }

    /** Ambil satu nilai pengaturan. */
    public static function get(string $key, $default = null)
    {
        $all = static::all();
        return $all[$key] ?? $default ?? (static::DEFAULTS[$key] ?? null);
    }

    /** Apakah pengaturan bertipe boolean aktif. */
    public static function bool(string $key): bool
    {
        return (string) static::get($key) === '1';
    }

    /** Simpan/perbarui satu nilai. */
    public static function put(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget('app_settings');
    }
}
