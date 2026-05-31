<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\User;

class SuperAdminController extends Controller
{
    /** Hub / dashboard Super Admin */
    public function index()
    {
        $stats = [
            'total_user'  => User::count(),
            'total_admin' => User::role('admin')->count(),
            'total_dosen' => User::role('dosen')->count(),
            'total_prodi' => ProgramStudi::count(),
            'total_mk'    => MataKuliah::count(),
        ];

        return view('super-admin.index', compact('stats'));
    }
}
