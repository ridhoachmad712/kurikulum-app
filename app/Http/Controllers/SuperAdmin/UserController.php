<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /** Daftar SEMUA pengguna lintas prodi, dengan filter role & search. */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role   = $request->input('role');

        $users = User::with(['roles', 'programStudi'])
            ->when($search, fn($q) => $q->where(fn($s) =>
                $s->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
            ))
            ->when($role, fn($q) => $q->whereHas('roles', fn($r) => $r->where('name', $role)))
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        $roleCounts = [
            'super_admin' => User::role('super_admin')->count(),
            'admin'       => User::role('admin')->count(),
            'kaprodi'     => User::role('kaprodi')->count(),
            'dosen'       => User::role('dosen')->count(),
        ];

        return view('super-admin.users.index', compact('users', 'search', 'role', 'roleCounts'));
    }

    public function create()
    {
        return view('super-admin.users.form', [
            'user'         => null,
            'programStudis'=> ProgramStudi::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => Hash::make($data['password']),
            'nip'               => $data['nip'] ?? null,
            'program_studi_id'  => $data['program_studi_id'],
            'email_verified_at' => now(),
        ]);

        $user->syncRoles($this->rolesFor($data['role']));

        ActivityLog::record('created', "membuat akun \"{$user->name}\" (role: {$data['role']})", 'Akun', $user->id, $user->name);

        return redirect()->route('super-admin.users.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('super-admin.users.form', [
            'user'          => $user,
            'programStudis' => ProgramStudi::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validateData($request, $user);

        // Cegah super admin menurunkan role dirinya sendiri (anti lockout)
        if ($user->id === auth()->id() && $data['role'] !== 'super_admin') {
            return back()->with('error', 'Anda tidak dapat menurunkan role akun Anda sendiri.');
        }

        $user->update([
            'name'             => $data['name'],
            'email'            => $data['email'],
            'nip'              => $data['nip'] ?? null,
            'program_studi_id' => $data['program_studi_id'],
            ...($data['password'] ? ['password' => Hash::make($data['password'])] : []),
        ]);

        $user->syncRoles($this->rolesFor($data['role']));

        ActivityLog::record('updated', "memperbarui akun \"{$user->name}\" (role: {$data['role']})", 'Akun', $user->id, $user->name);

        return redirect()->route('super-admin.users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }
        $nama = $user->name;
        $uid  = $user->id;
        $user->delete();
        ActivityLog::record('deleted', "menghapus akun \"{$nama}\"", 'Akun', $uid, $nama);
        return redirect()->route('super-admin.users.index')->with('success', 'Akun dihapus.');
    }

    /** Reset password ke default. */
    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('password')]);
        ActivityLog::record('password_reset', "mereset password akun \"{$user->name}\"", 'Akun', $user->id, $user->name);
        return back()->with('success', "Password {$user->name} direset ke default ('password').");
    }

    // ── Helpers ──────────────────────────────────────────────────

    private function validateData(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => ['required', 'email', Rule::unique('users', 'email')->ignore($user?->id)],
            'password'         => ($user ? 'nullable' : 'required') . '|string|min:6',
            'role'             => 'required|in:super_admin,admin,kaprodi,dosen',
            'program_studi_id' => 'required|exists:program_studis,id',
            'nip'              => 'nullable|string|max:50',
        ]);
    }

    /** Super admin selalu juga punya role admin (superset). */
    private function rolesFor(string $role): array
    {
        return $role === 'super_admin' ? ['super_admin', 'admin'] : [$role];
    }
}
