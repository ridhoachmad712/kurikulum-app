<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('super-admin.settings.index', [
            'settings' => Setting::all(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'app_name'           => 'required|string|max:100',
            'institution_name'   => 'required|string|max:150',
            'footer_text'        => 'required|string|max:200',
            'footer_credit_name' => 'required|string|max:100',
            'footer_credit_url'  => 'nullable|url|max:255',
            'footer_year'        => 'required|string|max:20',
            'break_reminder_min' => 'required|integer|min:1|max:240',
            'break_reminder_max' => 'required|integer|min:1|max:240|gte:break_reminder_min',
            'announcement_text'  => 'nullable|string|max:300',
            'announcement_type'  => 'required|in:info,warning',
        ]);

        // Field teks
        foreach ($data as $key => $value) {
            Setting::put($key, $value ?? '');
        }

        // Toggle (checkbox — absen = mati)
        Setting::put('maintenance_mode', $request->boolean('maintenance_mode') ? '1' : '0');
        Setting::put('break_reminder_enabled', $request->boolean('break_reminder_enabled') ? '1' : '0');
        Setting::put('announcement_enabled', $request->boolean('announcement_enabled') ? '1' : '0');

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
