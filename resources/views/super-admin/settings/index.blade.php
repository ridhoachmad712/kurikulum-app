@extends('layouts.app')
@section('title','Pengaturan Aplikasi')
@section('content')

<div class="max-w-2xl">
    <x-page-header title="Pengaturan Aplikasi" subtitle="Atur identitas, footer, mode pemeliharaan, dan pengingat istirahat"
                   back="{{ route('super-admin.index') }}" backLabel="Super Admin"/>

    <form method="POST" action="{{ route('super-admin.settings.update') }}" class="space-y-6">
        @csrf @method('PATCH')

        {{-- Identitas Aplikasi --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="w-1 h-4 rounded-full bg-blue-600"></span> Identitas Aplikasi
            </h2>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Nama Aplikasi</label>
                    <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name']) }}"
                           class="form-input @error('app_name') border-red-400 @enderror">
                    <p class="form-hint">Tampil di judul tab browser dan halaman login.</p>
                    @error('app_name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Nama Institusi</label>
                    <input type="text" name="institution_name" value="{{ old('institution_name', $settings['institution_name']) }}"
                           class="form-input @error('institution_name') border-red-400 @enderror">
                    <p class="form-hint">Mis. "S1 Manajemen · FEB UNM".</p>
                    @error('institution_name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Footer & Kredit --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="w-1 h-4 rounded-full bg-emerald-500"></span> Footer & Kredit
            </h2>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Teks Footer</label>
                    <input type="text" name="footer_text" value="{{ old('footer_text', $settings['footer_text']) }}"
                           class="form-input @error('footer_text') border-red-400 @enderror">
                    @error('footer_text')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nama Kredit</label>
                        <input type="text" name="footer_credit_name" value="{{ old('footer_credit_name', $settings['footer_credit_name']) }}"
                               class="form-input @error('footer_credit_name') border-red-400 @enderror">
                        @error('footer_credit_name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Tahun</label>
                        <input type="text" name="footer_year" value="{{ old('footer_year', $settings['footer_year']) }}"
                               class="form-input @error('footer_year') border-red-400 @enderror">
                        @error('footer_year')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="form-label">URL Kredit <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="footer_credit_url" value="{{ old('footer_credit_url', $settings['footer_credit_url']) }}"
                           class="form-input @error('footer_credit_url') border-red-400 @enderror" placeholder="https://...">
                    @error('footer_credit_url')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Mode Pemeliharaan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="w-1 h-4 rounded-full bg-amber-500"></span> Mode Pemeliharaan
            </h2>
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="maintenance_mode" value="1" {{ $settings['maintenance_mode'] === '1' ? 'checked' : '' }}
                       class="mt-0.5 w-5 h-5 rounded border-gray-300 text-amber-500 focus:ring-amber-400">
                <span>
                    <span class="text-sm font-medium text-gray-700">Aktifkan mode pemeliharaan</span>
                    <span class="block text-xs text-gray-400 mt-0.5">Saat aktif, semua pengguna (kecuali Super Admin) akan melihat halaman pemeliharaan dan tidak bisa mengakses aplikasi.</span>
                </span>
            </label>
            @if($settings['maintenance_mode'] === '1')
            <div class="mt-3 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 text-xs text-amber-700">
                ⚠ Mode pemeliharaan sedang <strong>AKTIF</strong> — hanya Super Admin yang bisa mengakses aplikasi.
            </div>
            @endif
        </div>

        {{-- Pengingat Istirahat Dosen --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <span class="w-1 h-4 rounded-full bg-violet-500"></span> Pengingat Istirahat Dosen
            </h2>
            <label class="flex items-start gap-3 cursor-pointer mb-4">
                <input type="checkbox" name="break_reminder_enabled" value="1" {{ $settings['break_reminder_enabled'] === '1' ? 'checked' : '' }}
                       class="mt-0.5 w-5 h-5 rounded border-gray-300 text-violet-500 focus:ring-violet-400">
                <span>
                    <span class="text-sm font-medium text-gray-700">Aktifkan pengingat istirahat</span>
                    <span class="block text-xs text-gray-400 mt-0.5">Popup pengingat istirahat (dengan tombol Kicau Mania & Mars MBG) untuk dosen.</span>
                </span>
            </label>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Interval Minimum (menit)</label>
                    <input type="number" name="break_reminder_min" min="1" max="240" value="{{ old('break_reminder_min', $settings['break_reminder_min']) }}"
                           class="form-input @error('break_reminder_min') border-red-400 @enderror">
                    @error('break_reminder_min')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Interval Maksimum (menit)</label>
                    <input type="number" name="break_reminder_max" min="1" max="240" value="{{ old('break_reminder_max', $settings['break_reminder_max']) }}"
                           class="form-input @error('break_reminder_max') border-red-400 @enderror">
                    @error('break_reminder_max')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <p class="form-hint mt-2">Popup muncul pada waktu acak antara interval minimum dan maksimum.</p>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-primary">Simpan Pengaturan</button>
        </div>
    </form>
</div>

@endsection
